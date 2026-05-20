<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query()->orderBy('id', 'asc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        }

        $customers = $query->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255|unique:customers,email',
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string'
            ]);

            Customer::create($request->only(['name', 'email', 'phone', 'address']));

            return redirect()->route('customers')->with('success', 'Customer successfully added!');
        }

        return view('customers.add');
    }

    public function edit(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string'
            ]);

            $customer->update($request->only(['name', 'email', 'phone', 'address']));

            return redirect()->route('customers')->with('success', 'Customer updated successfully!');
        }

        return view('customers.edit', compact('customer'));
    }

    public function delete($id)
    {
        $customer = Customer::withCount('orders')->findOrFail($id);

        if ($customer->orders_count > 0) {
            return back()->with('error', 'Cannot delete this customer because they have existing orders in the system.');
        }

        $customer->delete();
        return redirect()->route('customers')->with('success', 'Customer deleted successfully!');
    }
}
