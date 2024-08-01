<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;
    protected $fillable = ['item_id', 'type', 'quantity'];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}