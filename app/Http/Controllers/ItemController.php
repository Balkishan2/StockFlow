<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request){
       $data = Item::when($request->search, function ($query, $search) {
            $query->where('items.name', 'like', "%{$search}%");
        })
    ->paginate(10)
    ->appends(request()->query());


        if (empty($data)) {
            return back()->with('error', 'Data not found!');
        }
        return view('products.index', compact('data'));
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:100|unique:items,sku',
                'description' => 'nullable|string',
                'cost_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0'
            ]);

            Item::create([
                'name' => $request->name,
                'sku' => $request->sku,
                'description' => $request->description,
                'cost_price' => $request->cost_price,
                'selling_price' => $request->selling_price,
                'current_stock' => 0
            ]);

            return redirect()->route('products.listing')->with('success', 'Product successfully added!');
        }

        return view('products.add');
    }

    public function edit(Request $request, $id)
    {
        $product = Item::findOrFail($id);

        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string|max:255',
                'sku' => 'required|string|max:100|unique:items,sku,' . $product->id,
                'description' => 'nullable|string',
                'cost_price' => 'required|numeric|min:0',
                'selling_price' => 'required|numeric|min:0'
            ]);

            $product->update($request->only(['name', 'sku', 'description', 'cost_price', 'selling_price']));

            return redirect()->route('products.listing')->with('success', 'Product updated successfully!');
        }

        return view('products.edit', compact('product'));
    }

    public function delete($id)
    {
        $product = Item::withCount('orderItems')->findOrFail($id);

        if ($product->order_items_count > 0) {
            return back()->with('error', 'Cannot delete this product because it has been used in existing orders.');
        }

        $product->delete();
        return redirect()->route('products.listing')->with('success', 'Product deleted successfully!');
    }
}
