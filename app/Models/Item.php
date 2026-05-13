<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'sku', 'description', 'cost_price', 'selling_price', 'current_stock'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
