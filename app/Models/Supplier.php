<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'details',
        'status'
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('purchase_price')->withTimestamps();
    }
}
