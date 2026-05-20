<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ItemInventory;
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

                $customer = Customer::where('name', $request->customer_name)->first();
                if (!$customer) {
                    $customer = Customer::create([
                        'name' => $request->customer_name,
                        'email' => $request->customer_email,
                        'phone' => $request->customer_phone,
                        'address' => $request->shipping_address
                    ]);
                } else {
                    $customer->update([
                        'email' => $request->customer_email ?: $customer->email,
                        'phone' => $request->customer_phone ?: $customer->phone,
                        'address' => $request->shipping_address ?: $customer->address
                    ]);
                }

                $subtotal = 0;
                $totalTax = 0;
                $discount = $request->input('discount', 0);

                foreach($request->items as $itemData) {
                    $lineTotal = $itemData['qty'] * $itemData['price'];
                    $taxRate = isset($itemData['tax']) ? $itemData['tax'] : 0;
                    $lineTax = $lineTotal * ($taxRate / 100);

                    $subtotal += $lineTotal;
                    $totalTax += $lineTax;
                }

                $grandTotal = $subtotal + $totalTax - $discount;

                $order = Order::create([
                    'customer_id' => $customer->id,
                    'order_date' => now(),
                    'status' => $request->status,
                    'total_amount' => $grandTotal
                ]);

                $invoice = \App\Models\Invoice::create([
                    'order_id' => $order->id,
                    'customer_id' => $customer->id,
                    'invoice_date' => now(),
                    'due_date' => now()->addDays(7),
                    'status' => 'unpaid',
                    'subtotal' => $subtotal,
                    'total_tax' => $totalTax,
                    'total_discount' => $discount,
                    'total_amount' => $grandTotal,
                ]);

                foreach($request->items as $itemData) {
                    $item = Item::where('name', $itemData['name'])->first();
                    if (!$item) {
                        $item = Item::create([
                            'name' => $itemData['name'],
                            'sku' => 'SKU-' . strtoupper(Str::random(6)),
                            'description' => 'Auto-generated during order creation',
                            'cost_price' => $itemData['price'],
                            'selling_price' => $itemData['price'],
                            'current_stock' => 0 
                        ]);
                    }

                    $inventory = ItemInventory::firstOrCreate(
                        ['item_id' => $item->id],
                        ['current_stock' => 0]
                    );
                    $inventory->decrement('current_stock', $itemData['qty']);

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

                    \App\Models\InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'item_id' => $item->id,
                        'quantity' => $itemData['qty'],
                        'unit_price' => $itemData['price'],
                        'tax' => $taxRate,
                        'discount' => 0,
                        'total' => $lineTotal + $lineTax
                    ]);
                }

                DB::commit();

                return redirect()->route('invoices.print', $invoice->id)
                                 ->with('success', 'Order created successfully and Sales Invoice auto-generated.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Error creating order: ' . $e->getMessage())->withInput();
            }
        }
        $items = Item::all();
        return view('orders.add', compact('items'));
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

                foreach($order->orderItems as $existingItem) {
                    $inventory = ItemInventory::firstOrCreate(
                        ['item_id' => $existingItem->item_id],
                        ['current_stock' => 0]
                    );
                    $inventory->increment('current_stock', $existingItem->quantity);
                }
                
                $order->orderItems()->delete();

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

                    $inventory = ItemInventory::firstOrCreate(
                        ['item_id' => $item->id],
                        ['current_stock' => 0]
                    );
                    $inventory->decrement('current_stock', $itemData['qty']);

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

                $order->update([
                    'status' => $request->status,
                    'total_amount' => $grandTotal
                ]);

                DB::commit();

                return redirect()->route('orders.view', $order->id)
                                 ->with('success', 'Order updated successfully! .');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Error updating order: ' . $e->getMessage())->withInput();
            }
        }

        $items = Item::all();
        return view('orders.edit', compact('order', 'items'));
    }
}
