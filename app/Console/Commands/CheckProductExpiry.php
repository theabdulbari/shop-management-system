<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PurchaseItem;
use App\Models\ExpiryNotification;
use Carbon\Carbon;

class CheckProductExpiry extends Command
{
    protected $signature = 'check:expiry';
    protected $description = 'Notify before 15 days of product expiry';

    public function handle()
    {
        $today = Carbon::today();
        $limitDate = Carbon::today()->addDays(15);

        $items = PurchaseItem::whereBetween('expiry_date', [$today, $limitDate])->get();

        foreach ($items as $item) {

            // Prevent duplicate notification
            $exists = ExpiryNotification::where('purchase_item_id', $item->id)->first();
            if ($exists) continue;

            ExpiryNotification::create([
                'purchase_item_id' => $item->id,
                'product_id' => $item->product_id,
                'expiry_date' => $item->expiry_date,
                'notified' => false,
            ]);

            // You can send email, SMS, Livewire popup, etc.
        }

        return Command::SUCCESS;
    }
}
