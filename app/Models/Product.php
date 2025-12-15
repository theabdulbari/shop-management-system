<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'sku',
        'unit',
        'purchase_price',
        'sell_price',
        'status',
        'barcode',
        'description',
        'purchase_price',
        'stock_qty',
        'stock_alert_qty',
        'track_inventory',
        'image',
        'supplier_id'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function stockTransactions(){ return $this->hasMany(StockTransaction::class); }

    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class)->withPivot('purchase_price')->withTimestamps();
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    // public static function boot()
    // {
    //     parent::boot();


    //     // Auto-generate SKU and Barcode when creating a product
    //     static::creating(function ($product) {
    //         // --- Auto-generate SKU ---
    //         if (!$product->sku) {
    //             $categoryCode = $product->category ? strtoupper(substr($product->category->name, 0, 3)) : 'PRD';
    //             $randomNumber = str_pad(Product::max('id') + 1 ?? 1, 4, '0', STR_PAD_LEFT);
    //             $product->sku = $categoryCode . '-' . $randomNumber;
    //         }

    //         // --- Auto-generate Barcode ---
    //         if (!$product->barcode) {
    //             // EAN-13 numeric barcode (13 digits)
    //             $prefix = 'MM'; // you can choose your company/store prefix
    //             $uniqueNumber = str_pad(Product::max('id') + 1 ?? 1, 10, '0', STR_PAD_LEFT);
    //             $product->barcode = $prefix . $uniqueNumber;
    //         }
    //     });
    // }
}
