<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\ItemInventory;
use Illuminate\Support\Facades\DB;

class ReportsController extends Controller
{
    public function sales(Request $request)
    {
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        if ($startDateInput) {
            $startDate = Carbon::parse($startDateInput)->startOfDay();
        } else {
            $startDate = Carbon::now()->subDays(30)->startOfDay();
        }

        if ($endDateInput) {
            $endDate = Carbon::parse($endDateInput)->endOfDay();
        } else {
            $endDate = Carbon::now()->endOfDay();
        }

       
        $query = Invoice::where('status', '!=', 'draft')
            ->whereBetween('invoice_date', [$startDate, $endDate]);

        $totalInvoices = (clone $query)->count();
        $totalRevenue = (clone $query)->sum('total_amount');
        $totalTax = (clone $query)->sum('total_tax');
        $totalDiscount = (clone $query)->sum('total_discount');
        
        $totalItemsSold = InvoiceItem::whereHas('invoice', function ($q) use ($startDate, $endDate) {
            $q->where('status', '!=', 'draft')
              ->whereBetween('invoice_date', [$startDate, $endDate]);
        })->sum('quantity');

        $invoices = $query->with('customer')->latest('invoice_date')->paginate(10)->appends($request->query());

        return view('reports.sales', compact(
            'invoices',
            'totalInvoices',
            'totalRevenue',
            'totalTax',
            'totalDiscount',
            'totalItemsSold',
            'startDate',
            'endDate'
        ));
    }

    public function exportSalesCsv(Request $request)
    {
        $startDateInput = $request->input('start_date');
        $endDateInput = $request->input('end_date');

        if ($startDateInput) {
            $startDate = Carbon::parse($startDateInput)->startOfDay();
        } else {
            $startDate = Carbon::now()->subDays(30)->startOfDay();
        }

        if ($endDateInput) {
            $endDate = Carbon::parse($endDateInput)->endOfDay();
        } else {
            $endDate = Carbon::now()->endOfDay();
        }

        $fileName = 'sales_report_' . $startDate->format('Ymd') . '_to_' . $endDate->format('Ymd') . '.csv';

        $invoices = Invoice::where('status', '!=', 'draft')
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->with('customer')
            ->orderBy('invoice_date', 'asc')
            ->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Invoice ID', 
            'Date', 
            'Customer Name', 
            'Customer Email', 
            'Subtotal (Rs)', 
            'Total Tax (Rs)', 
            'Total Discount (Rs)', 
            'Grand Total (Rs)', 
            'Status'
        ];

        $callback = function() use($invoices, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($invoices as $invoice) {
                fputcsv($file, [
                    $invoice->id,
                    $invoice->invoice_date->format('d M, Y'),
                    $invoice->customer->name,
                    $invoice->customer->email ?? '-',
                    number_format($invoice->subtotal, 2, '.', ''),
                    number_format($invoice->total_tax, 2, '.', ''),
                    number_format($invoice->total_discount, 2, '.', ''),
                    number_format($invoice->total_amount, 2, '.', ''),
                    strtoupper($invoice->status)
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function inventory(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status'); // 'low', 'out', 'in'

        // Build base query
        $query = ItemInventory::join('items', 'item_inventory.item_id', '=', 'items.id')
            ->select('item_inventory.*', 'items.name', 'items.sku', 'items.cost_price', 'items.selling_price');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('items.name', 'like', "%{$search}%")
                  ->orWhere('items.sku', 'like', "%{$search}%");
            });
        }

        if ($statusFilter === 'out') {
            $query->where('item_inventory.current_stock', '=', 0);
        } elseif ($statusFilter === 'low') {
            $query->where('item_inventory.current_stock', '>', 0)
                  ->where('item_inventory.current_stock', '<', 10);
        } elseif ($statusFilter === 'in') {
            $query->where('item_inventory.current_stock', '>=', 10);
        }

        // Get calculations for cards
        $totalItems = DB::table('item_inventory')->count();
        $totalCostValue = DB::table('item_inventory')
            ->join('items', 'item_inventory.item_id', '=', 'items.id')
            ->sum(DB::raw('item_inventory.current_stock * items.cost_price'));
            
        $totalRetailValue = DB::table('item_inventory')
            ->join('items', 'item_inventory.item_id', '=', 'items.id')
            ->sum(DB::raw('item_inventory.current_stock * items.selling_price'));

        $potentialProfit = $totalRetailValue - $totalCostValue;
        
        $lowStockCount = DB::table('item_inventory')
            ->where('current_stock', '>', 0)
            ->where('current_stock', '<', 10)
            ->count();
            
        $outOfStockCount = DB::table('item_inventory')
            ->where('current_stock', '=', 0)
            ->count();

        // Paginate results
        $inventoryData = $query->orderBy('items.name', 'asc')->paginate(10)->appends($request->query());

        return view('reports.inventory', compact(
            'inventoryData',
            'totalItems',
            'totalCostValue',
            'totalRetailValue',
            'potentialProfit',
            'lowStockCount',
            'outOfStockCount',
            'search',
            'statusFilter'
        ));
    }

    public function exportInventoryCsv(Request $request)
    {
        $search = $request->input('search');
        $statusFilter = $request->input('status');

        $query = ItemInventory::join('items', 'item_inventory.item_id', '=', 'items.id')
            ->select('item_inventory.*', 'items.name', 'items.sku', 'items.cost_price', 'items.selling_price');

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('items.name', 'like', "%{$search}%")
                  ->orWhere('items.sku', 'like', "%{$search}%");
            });
        }

        if ($statusFilter === 'out') {
            $query->where('item_inventory.current_stock', '=', 0);
        } elseif ($statusFilter === 'low') {
            $query->where('item_inventory.current_stock', '>', 0)
                  ->where('item_inventory.current_stock', '<', 10);
        } elseif ($statusFilter === 'in') {
            $query->where('item_inventory.current_stock', '>=', 10);
        }

        $items = $query->orderBy('items.name', 'asc')->get();
        $fileName = 'inventory_report_' . Carbon::now()->format('Ymd_His') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'Item ID', 
            'SKU', 
            'Item Name', 
            'Current Stock', 
            'Cost Price (Rs)', 
            'Selling Price (Rs)', 
            'Total Cost Value (Rs)', 
            'Total Retail Value (Rs)', 
            'Potential Profit (Rs)',
            'Status'
        ];

        $callback = function() use($items, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($items as $item) {
                $totalCost = $item->current_stock * $item->cost_price;
                $totalRetail = $item->current_stock * $item->selling_price;
                $profit = $totalRetail - $totalCost;
                
                $status = 'IN STOCK';
                if ($item->current_stock == 0) {
                    $status = 'OUT OF STOCK';
                } elseif ($item->current_stock < 10) {
                    $status = 'LOW STOCK';
                }

                fputcsv($file, [
                    $item->id,
                    $item->sku,
                    $item->name,
                    $item->current_stock,
                    number_format($item->cost_price, 2, '.', ''),
                    number_format($item->selling_price, 2, '.', ''),
                    number_format($totalCost, 2, '.', ''),
                    number_format($totalRetail, 2, '.', ''),
                    number_format($profit, 2, '.', ''),
                    $status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
