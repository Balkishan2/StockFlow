<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\Item;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $customersQuery = Customer::query();
        $ordersQuery = Order::query();
        $invoicesQuery = Invoice::query();
        $productsQuery = Item::query();

        if ($startDate) {
            $customersQuery->whereDate('created_at', '>=', $startDate);
            $ordersQuery->whereDate('order_date', '>=', $startDate);
            $invoicesQuery->whereDate('invoice_date', '>=', $startDate);
            $productsQuery->whereDate('created_at', '>=', $startDate);
        }

        if ($endDate) {
            $customersQuery->whereDate('created_at', '<=', $endDate);
            $ordersQuery->whereDate('order_date', '<=', $endDate);
            $invoicesQuery->whereDate('invoice_date', '<=', $endDate);
            $productsQuery->whereDate('created_at', '<=', $endDate);
        }

        $totalCustomers = $customersQuery->count();
        $totalOrders = $ordersQuery->count();
        $totalRevenue = $invoicesQuery->sum('total_amount');
        $totalProducts = $productsQuery->count();

        return view('dashboard.dashboard', compact(
            'totalCustomers',
            'totalOrders',
            'totalRevenue',
            'totalProducts',
            'startDate',
            'endDate'
        ));
    }
}
