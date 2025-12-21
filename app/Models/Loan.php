<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Loan extends Model
{
    protected $fillable = [
        'payer_name',
        'phone',
        'address',
        'loan_date',
        'amount',
        'paid',
        'due',
        'possible_paid_date',
        'paid_date',
        'status',
        'note',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'possible_paid_date' => 'date',
        'amount' => 'float',
        'paid' => 'float',
        'due' => 'float',
    ];
    protected $dates = ['loan_date', 'paid_date'];

      public function payments()
    {
        return $this->hasMany(LoanPayment::class);
    }
public function recalculate(bool $save = true): void
{
    $this->due = round($this->amount - $this->paid, 4);

    if ($this->paid <= 0) {
        $this->status = 'due';
        $this->paid_date = null;

    } elseif ($this->paid < $this->amount) {
        $this->status = 'partial';
        $this->paid_date = null;

    } else {
        $this->status = 'paid';
        $this->paid = $this->amount;
        $this->due = 0;

        // only set once
        if (!$this->paid_date) {
            $this->paid_date = Carbon::today();
        }
    }

    if ($save) {
        $this->save();
    }
}


public function setStatus(string $status): void
{
    $status = strtolower($status);

    if ($status === 'paid') {
        $amountToPay = $this->due;

        if ($amountToPay > 0) {
            $this->paid += $amountToPay;

            // payment history
            $this->payments()->create([
                'amount' => $amountToPay,
                'paid_date' => now(),
            ]);
        }
    }

    $this->recalculate();
}


}