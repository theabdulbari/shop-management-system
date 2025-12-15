<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class  Sale extends Model
{
    
    use HasFactory;
protected $fillable = [
        'customer_id', 'invoice_id', 'user_id', 'sale_date', 'discount', 'shipping',
        'paid', 'due', 'grand_total', 'total_amount', 'payment_status'
    ];

    public function items(){ return $this->hasMany(SaleItem::class); }
    public function customer(){ return $this->belongsTo(Customer::class); }
    public function payments(){ return $this->hasMany(Payment::class); }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
