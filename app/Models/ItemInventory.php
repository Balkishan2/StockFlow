<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemInventory extends Model
{
   protected $table = 'item_inventory';
   protected $fillable = [
        "item_id",
        "current_stock",
    ];
   
   public function item(){
    return $this->belongsTo(Item::class);
   }
}
