<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;

use App\Livewire\Category\CategoryIndex;
use App\Livewire\Category\CategoryCreate;
use App\Livewire\Category\CategoryEdit;

use App\Livewire\Products\ProductIndex;
use App\Livewire\Products\ProductCreate;
use App\Livewire\Products\ProductEdit;

use App\Livewire\Supplier\SupplierIndex;
use App\Livewire\Supplier\SupplierCreate;
use App\Livewire\Supplier\SupplierEdit;

use App\Livewire\Purchase\PurchaseIndex;
use App\Livewire\Purchase\PurchaseCreate;
use App\Livewire\Purchase\PurchaseEdit;

use App\Livewire\Sales\SaleIndex;
use App\Livewire\Sales\SaleEdit;
use App\Livewire\Sales\SaleCreate;

use App\Livewire\Stock\StockIndex;
use App\Livewire\Stock\StockTransactionLog;

use App\Livewire\Dashboard\Analytics;

use App\Livewire\Users\Index as UserIndex;
use App\Livewire\Users\Create as UserCreate;
use App\Livewire\Users\Edit as UserEdit;

use App\Livewire\Reports\SalesReport;
use App\Livewire\Reports\TopSellingProducts;
use App\Livewire\Reports\StockReport;


use App\Livewire\Customers\CustomerIndex;
use App\Livewire\Customers\CustomerCreate;
use App\Livewire\Customers\CustomerEdit;

use App\Livewire\Expenses\{
    ExpenseIndex,
    ExpenseCreate,
    ExpenseEdit,
    MonthlyExpenseChart,
    ExpenseCategoryIndex
};



/*
|--------------------------------------------------------------------------
| Redirect root to dashboard
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => redirect()->route('dashboard'));


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', Analytics::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| User Management (Admin Only)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', UserIndex::class)->name('users.index');
    Route::get('/users/create', UserCreate::class)->name('users.create');
    Route::get('/users/{id}/edit', UserEdit::class)->name('users.edit');

    Route::resource('products', ProductController::class);
});


/*
|--------------------------------------------------------------------------
| Livewire Modules (Auth Required)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Categories
    Route::get('/categories', CategoryIndex::class)->name('categories.index');
    Route::get('/categories/create', CategoryCreate::class)->name('categories.create');
    Route::get('/categories/{category}/edit', CategoryEdit::class)->name('categories.edit');

    // Products
    Route::get('/products', ProductIndex::class)->name('products.index');
    Route::get('/products/create', ProductCreate::class)->name('products.create');
    Route::get('/products/{product}/edit', ProductEdit::class)->name('products.edit');

    // Suppliers
    Route::get('/suppliers', SupplierIndex::class)->name('suppliers.index');
    Route::get('/suppliers/create', SupplierCreate::class)->name('suppliers.create');
    Route::get('/suppliers/{supplier}/edit', SupplierEdit::class)->name('suppliers.edit');

    // Purchases
    Route::get('/purchases', PurchaseIndex::class)->name('purchases.index');
    Route::get('/purchases/create', PurchaseCreate::class)->name('purchases.create');
    Route::get('/purchases/{purchase}/edit', PurchaseEdit::class)->name('purchases.edit');

    // Customers
    Route::get('/customers', CustomerIndex::class)->name('customers.index');
    Route::get('/customers/create', CustomerCreate::class)->name('customers.create');
    Route::get('/customers/edit/{customer}', CustomerEdit::class)->name('customers.edit');


    // Sales
    Route::get('/sales', SaleIndex::class)->name('sales.index');
    Route::get('/sales/{sale}/edit', SaleEdit::class)->name('sales.edit');
    Route::get('/sales/create', SaleCreate::class)->name('sales.create');

    // Stock
    Route::get('/stock', StockIndex::class)->name('stock.index');
    Route::get('/stock/transactions', StockTransactionLog::class)->name('stock.transactions');

    // Expenses
    Route::get('/expenses', ExpenseIndex::class)->name('expenses.index');
    Route::get('/expenses/create', ExpenseCreate::class)->name('expenses.create');
    Route::get('/expenses/{expense}/edit', ExpenseEdit::class)->name('expenses.edit');
    Route::get('/expenses/charts/monthly', MonthlyExpenseChart::class)->named('expenses.charts.monthly');  
    Route::get('/expense-categories', ExpenseCategoryIndex::class)->name('expense.categories'); 

    // Reports
    Route::get('/reports/sales', SalesReport::class)->name('reports.sales');
    Route::get('/reports/stock', StockReport::class)->name('reports.stock');
    Route::get('/reports/top-selling', TopSellingProducts::class)->name('reports.top-selling');
});


/*
|--------------------------------------------------------------------------
| Role-Based Access for POS / Manager / Sales
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:manager'])->group(function () {
    // Manager-specific routes
});

Route::middleware(['auth', 'role:sales'])->group(function () {
    // POS Route example:
    // Route::get('/pos', Pos::class)->name('pos');
});

require __DIR__.'/auth.php';