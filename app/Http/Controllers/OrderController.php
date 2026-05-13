<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\InvoiceController;

class OrderController
{
    public function index(Request $request){
        $query = Order::with('customer')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $orderId = preg_replace('/[^0-9]/', '', $search);
                if ($orderId) {
                    $q->where('id', (int)$orderId);
                }
                $q->orWhereHas('customer', function($customerQuery) use ($search) {
                    $customerQuery->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('order_date', $request->date);
        }

        $orders = $query->paginate(10);
        
        return view('orders.index', compact('orders'));
    }

    public function add(Request $request) {
        if ($request->isMethod('post')) {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'nullable|email|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'shipping_address' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.name' => 'required|string|max:255',
                'items.*.qty' => 'required|numeric|min:1',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.tax' => 'nullable|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'status' => 'required|in:pending,processing,completed'
            ]);

            try {
                DB::beginTransaction();

                // 1. Find or create the Customer
                $customer = Customer::where('name', $request->customer_name)->first();
                if (!$customer) {
                    $customer = Customer::create([
                        'name' => $request->customer_name,
                        'email' => $request->customer_email,
                        'phone' => $request->customer_phone,
                        'address' => $request->shipping_address
                    ]);
                } else {
                    // Update details if provided and missing
                    $customer->update([
                        'email' => $request->customer_email ?: $customer->email,
                        'phone' => $request->customer_phone ?: $customer->phone,
                        'address' => $request->shipping_address ?: $customer->address
                    ]);
                }

                $subtotal = 0;
                $totalTax = 0;
                $discount = $request->input('discount', 0);

                // We need to calculate totals from the items to be safe (never trust client-side grand totals)
                foreach($request->items as $itemData) {
                    $lineTotal = $itemData['qty'] * $itemData['price'];
                    $taxRate = isset($itemData['tax']) ? $itemData['tax'] : 0;
                    $lineTax = $lineTotal * ($taxRate / 100);

                    $subtotal += $lineTotal;
                    $totalTax += $lineTax;
                }

                $grandTotal = $subtotal + $totalTax - $discount;

                // 2. Create the Order
                $order = Order::create([
                    'customer_id' => $customer->id,
                    'order_date' => now(),
                    'status' => $request->status,
                    'total_amount' => $grandTotal
                ]);

                // 3. Process Items and OrderItems
                foreach($request->items as $itemData) {
                    // Find or create item based on description/name
                    $item = Item::where('name', $itemData['name'])->first();
                    if (!$item) {
                        $item = Item::create([
                            'name' => $itemData['name'],
                            'sku' => 'SKU-' . strtoupper(Str::random(6)),
                            'description' => 'Auto-generated during order creation',
                            'cost_price' => $itemData['price'],
                            'selling_price' => $itemData['price'],
                            'current_stock' => 0 // Start at 0, goes negative if no stock exists
                        ]);
                    }

                    // Deduct from inventory
                    $item->decrement('current_stock', $itemData['qty']);

                    $lineTotal = $itemData['qty'] * $itemData['price'];
                    $taxRate = isset($itemData['tax']) ? $itemData['tax'] : 0;
                    $lineTax = $lineTotal * ($taxRate / 100);

                    OrderItem::create([
                        'order_id' => $order->id,
                        'item_id' => $item->id,
                        'quantity' => $itemData['qty'],
                        'unit_price' => $itemData['price'],
                        'total' => $lineTotal + $lineTax
                    ]);
                }

                DB::commit();

                // Redirect to invoice page automatically upon saving order
                return redirect()->route('orders.invoice', $order->id)
                                 ->with('success', 'Order created successfully! Inventory stock has been updated.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Error creating order: ' . $e->getMessage())->withInput();
            }
        }
        return view('orders.add');
    }

    public function show($id) {
        $order = Order::with(['customer', 'orderItems.item'])->findOrFail($id);
        return view('orders.view', compact('order'));
    }

    public function edit(Request $request, $id) {
        $order = Order::with(['customer', 'orderItems.item'])->findOrFail($id);

        if ($request->isMethod('post')) {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'nullable|email|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'shipping_address' => 'nullable|string',
                'items' => 'required|array|min:1',
                'items.*.name' => 'required|string|max:255',
                'items.*.qty' => 'required|numeric|min:1',
                'items.*.price' => 'required|numeric|min:0',
                'items.*.tax' => 'nullable|numeric|min:0',
                'discount' => 'nullable|numeric|min:0',
                'status' => 'required|in:pending,processing,completed,cancelled'
            ]);

            try {
                DB::beginTransaction();

                // Restore Inventory from existing order
                foreach($order->orderItems as $existingItem) {
                    $existingItem->item->increment('current_stock', $existingItem->quantity);
                }
                
                // Delete old order items
                $order->orderItems()->delete();

                // Update Customer
                $customer = $order->customer;
                $customer->update([
                    'name' => $request->customer_name,
                    'email' => $request->customer_email,
                    'phone' => $request->customer_phone,
                    'address' => $request->shipping_address
                ]);

                $subtotal = 0;
                $totalTax = 0;
                $discount = $request->input('discount', 0);

                // Process new items and deduct inventory
                foreach($request->items as $itemData) {
                    $item = Item::where('name', $itemData['name'])->first();
                    if (!$item) {
                        $item = Item::create([
                            'name' => $itemData['name'],
                            'sku' => 'SKU-' . strtoupper(Str::random(6)),
                            'description' => 'Auto-generated during order edit',
                            'cost_price' => $itemData['price'],
                            'selling_price' => $itemData['price'],
                            'current_stock' => 0
                        ]);
                    }

                    // Deduct from inventory
                    $item->decrement('current_stock', $itemData['qty']);

                    $lineTotal = $itemData['qty'] * $itemData['price'];
                    $taxRate = isset($itemData['tax']) ? $itemData['tax'] : 0;
                    $lineTax = $lineTotal * ($taxRate / 100);

                    $subtotal += $lineTotal;
                    $totalTax += $lineTax;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'item_id' => $item->id,
                        'quantity' => $itemData['qty'],
                        'unit_price' => $itemData['price'],
                        'total' => $lineTotal + $lineTax
                    ]);
                }

                $grandTotal = $subtotal + $totalTax - $discount;

                // Update Order
                $order->update([
                    'status' => $request->status,
                    'total_amount' => $grandTotal
                ]);

                DB::commit();

                return redirect()->route('orders.view', $order->id)
                                 ->with('success', 'Order updated successfully! Inventory stock has been adjusted.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Error updating order: ' . $e->getMessage())->withInput();
            }
        }

        return view('orders.edit', compact('order'));
    }
}
