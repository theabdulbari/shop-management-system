<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Customer;

class DashboardController extends Controller
{
    public function index()
{
    return view('dashboard', [
        'today_sales' => Sale::whereDate('created_at', today())->sum('total'),
        'last_7_days' => Sale::where('created_at', '>=', now()->subDays(7))->sum('total'),
        'last_month'  => Sale::whereMonth('created_at', now()->month)->sum('total'),
        'year_sales'  => Sale::whereYear('created_at', now()->year)->sum('total'),
        'total_products' => Product::count(),
        'total_customers' => Customer::count(),
    ]);
}
}
