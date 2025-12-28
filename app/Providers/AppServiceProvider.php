<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Setting;
use App\Models\Product;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();
        view()->composer('*', function ($view) {
            $view->with('appSetting', Setting::first());
        });

        view()->composer('*', function ($view) {
            $view->with(
                'lowStockProducts',
                Product::where('stock_qty', '<=', 5)->orderBy('stock_qty')->get()
            );
        });
    }
}
