<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = ['sale_id'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
    
    protected static function boot()
    {
        parent::boot();

        static::created(function ($invoice) {
            $prefix = "MM";   // Your fixed prefix (change if needed)
            $customerId = $invoice->customer_id ?? 1;
            $saleId = $invoice->id;
            $date = date('Ymd');

            $invoiceNumber = "{$prefix}-{$customerId}-{$saleId}-{$date}";

            // Update invoice with generated number
            $invoice->invoice_number = $invoiceNumber;
            $invoice->save();
        });
    }
}
