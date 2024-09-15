<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'stock', 'price', 'min_stock'
    ];

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
