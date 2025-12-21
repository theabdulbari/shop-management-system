<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// app/Models/LoanPayment.php
class LoanPayment extends Model
{
    protected $fillable = ['loan_id', 'amount', 'paid_date', 'note'];
    protected $casts = [
        'paid_date' => 'date',
    ];
    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }
}
