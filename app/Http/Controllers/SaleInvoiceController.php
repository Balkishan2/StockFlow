<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class SaleInvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('customer')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('invoice_date', $request->date);
        }

        $invoices = $query->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'nullable|email|max:255',
                'customer_phone' => 'nullable|string|max:50',
                'customer_address' => 'nullable|string',
                
                'invoice_date' => 'required|date',
                'due_date' => 'nullable|date|after_or_equal:invoice_date',
                
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|exists:items,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unit_price' => 'required|numeric|min:0',
                'items.*.tax' => 'nullable|numeric|min:0',
                'items.*.discount' => 'nullable|numeric|min:0',
            ]);

            try {
                DB::beginTransaction();

                // Dynamically create or fetch the customer
                $customer = Customer::firstOrCreate(
                    ['name' => $request->customer_name],
                    [
                        'email' => $request->customer_email,
                        'phone' => $request->customer_phone,
                        'address' => $request->customer_address
                    ]
                );

                $subtotal = 0;
                $totalTax = 0;
                $totalDiscount = 0;

                foreach ($request->items as $item) {
                    $qty = $item['quantity'];
                    $price = $item['unit_price'];
                    $taxPct = $item['tax'] ?? 0;
                    $discountAmt = $item['discount'] ?? 0;

                    $lineTotal = ($qty * $price);
                    $lineTax = $lineTotal * ($taxPct / 100);

                    $subtotal += $lineTotal;
                    $totalTax += $lineTax;
                    $totalDiscount += $discountAmt;
                }

                $grandTotal = $subtotal + $totalTax - $totalDiscount;

                $action = $request->input('action', 'draft');
                $status = ($action === 'complete') ? 'unpaid' : 'draft';

                $invoice = Invoice::create([
                    'customer_id' => $customer->id,
                    'invoice_date' => $request->invoice_date,
                    'due_date' => $request->due_date,
                    'status' => $status,
                    'subtotal' => $subtotal,
                    'total_tax' => $totalTax,
                    'total_discount' => $totalDiscount,
                    'total_amount' => $grandTotal,
                ]);

                foreach ($request->items as $item) {
                    $qty = $item['quantity'];
                    $price = $item['unit_price'];
                    $taxPct = $item['tax'] ?? 0;
                    $discountAmt = $item['discount'] ?? 0;

                    $lineTotal = ($qty * $price);
                    $lineTax = $lineTotal * ($taxPct / 100);
                    $finalLineTotal = $lineTotal + $lineTax - $discountAmt;

                    InvoiceItem::create([
                        'invoice_id' => $invoice->id,
                        'item_id' => $item['item_id'],
                        'quantity' => $qty,
                        'unit_price' => $price,
                        'tax' => $taxPct,
                        'discount' => $discountAmt,
                        'total' => $finalLineTotal
                    ]);
                }

                DB::commit();

                if ($status === 'unpaid') {
                    // It was "Completed", redirect to print
                    return redirect()->route('invoices.print', $invoice->id)->with('success', 'Sales Invoice created and completed.');
                }

                return redirect()->route('invoices')->with('success', 'Sales Invoice saved as draft.');

            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Error creating invoice: ' . $e->getMessage())->withInput();
            }
        }

        $customers = Customer::orderBy('name')->get();
        $items = Item::orderBy('name')->get();
        return view('invoices.add', compact('customers', 'items'));
    }

    public function edit(Request $request, $id)
    {
        $invoice = Invoice::with(['customer', 'invoiceItems.item'])->findOrFail($id);

        if ($request->isMethod('post')) {
            $request->validate([
                'due_date' => 'nullable|date',
                'status' => 'required|in:draft,unpaid,paid,overdue',
                // For simplicity, we only allow updating status and due date here,
                // but if it's a draft, they should theoretically be able to edit items.
                // To keep it clean, we'll just handle status updates for now or allow full edit if draft.
            ]);

            $action = $request->input('action', 'save');
            $newStatus = $request->status;

            if ($action === 'complete') {
                $newStatus = 'unpaid';
            }

            $invoice->update([
                'due_date' => $request->due_date,
                'status' => $newStatus,
            ]);

            if ($action === 'complete') {
                return redirect()->route('invoices.print', $invoice->id)->with('success', 'Invoice completed successfully.');
            }

            return redirect()->route('invoices')->with('success', 'Invoice updated successfully.');
        }

        return view('invoices.edit', compact('invoice'));
    }

    public function print($id)
    {
        $invoice = Invoice::with(['customer', 'invoiceItems.item'])->findOrFail($id);
        return view('invoices.print', compact('invoice'));
    }

    public function delete($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        return redirect()->route('invoices')->with('success', 'Invoice deleted successfully.');
    }
}
