<?php

namespace App\Http\Controllers;

use App\Models\ItemInventory;
use App\Models\Item;
use Illuminate\Http\Request;

class ItemInventoryController extends Controller
{
    public function index(Request $request)
    {
        $data = ItemInventory::join('items', 'item_inventory.item_id', '=', 'items.id')
        ->when($request->search, function ($query, $search) {
            $query->where('items.name', 'like', "%{$search}%");
        })
    ->paginate(10, ['items.name', 'item_inventory.*'])
    ->appends(request()->query());


        if (empty($data)) {
            return back()->with('error', 'Data not found!');
        }
        return view('item_inventory.index', compact('data'));
    }

    public function add(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'item_id' => 'required|exists:items,id',
                'current_stock' => 'required|integer|min:0',
            ]);

            $inventory = ItemInventory::firstOrNew(['item_id' => $request->item_id]);
            $inventory->current_stock = ($inventory->current_stock ?? 0) + $request->current_stock;
            $inventory->save();

            return redirect()->route('inventory')->with('success', 'Inventory successfully added!');
        }

        $items = Item::all();
        return view('item_inventory.add', compact('items'));
    }
}
