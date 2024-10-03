<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'stock', 'price', 'min_stock', 'supplier_id'
    ];

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
