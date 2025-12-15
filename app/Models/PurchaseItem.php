<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'weight',
        'weight_unit',
        'unit_price',
        'subtotal',
        'expiry_date'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
