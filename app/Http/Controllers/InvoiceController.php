<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class InvoiceController extends Controller
{
    public function show($id) {
        $order = Order::with(['customer', 'orderItems.item'])->findOrFail($id);
        return view('orders.invoice', compact('order'));
    }
}
