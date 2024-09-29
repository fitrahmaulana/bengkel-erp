<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    CONST TYPE_SERVICE = 'service';
    CONST TYPE_PRODUCT = 'product';
    CONST TYPE_CUSTOM = 'custom';

    protected $fillable = ['invoice_id', 'item_id', 'name', 'quantity', 'price', 'total', 'type'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
