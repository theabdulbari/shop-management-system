<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'ERP System' }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    @livewireStyles
</head>

<body>
    <!-- TOP NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3">
        <a class="navbar-brand" href="{{ route('dashboard') }}">ERP</a>

        <div class="ms-auto text-white">
            {{ Auth::user()->name }}
            <a class="btn btn-sm btn-danger ms-3" href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
        </div>
    </nav>

    <div class="d-flex">
        <!-- SIDEBAR -->
        <div class="bg-light border-end" style="width: 250px; min-height: 100vh;">
            <div class="list-group list-group-flush">

                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">📊 Dashboard</a>

                {{-- <div class="fw-bold px-3 pt-3 text-secondary small">CUSTOMERS</div> --}}
                <a href="{{ route('customers.index') }}" class="list-group-item list-group-item-action">👥 Customers</a>
                <a href="{{ route('expenses.index') }}" class="list-group-item list-group-item-action">💵 Expense</a>

                <div class="fw-bold px-3 pt-3 text-secondary small">INVENTORY</div>
                <a href="{{ route('categories.index') }}" class="list-group-item list-group-item-action">📁 Categories</a>
                <a href="{{ route('products.index') }}" class="list-group-item list-group-item-action">📦 Products</a>
                <a href="{{ route('suppliers.index') }}" class="list-group-item list-group-item-action">🚚 Suppliers</a>

                <div class="fw-bold px-3 pt-3 text-secondary small">TRANSACTIONS</div>
                <a href="{{ route('purchases.index') }}" class="list-group-item list-group-item-action">🛒 Purchases</a>
                <a href="{{ route('sales.index') }}" class="list-group-item list-group-item-action">💵 Sales</a>
                <a href="{{ route('stock.index') }}" class="list-group-item list-group-item-action">📉 Stock</a>

                <div class="fw-bold px-3 pt-3 text-secondary small">REPORTS</div>
                {{-- <a href="{{ route('reports.sales') }}" class="list-group-item list-group-item-action">📈 Sales Report</a> --}}
                {{-- <a href="{{ route('reports.stock') }}" class="list-group-item list-group-item-action">📦 Stock Report</a> --}}

                <div class="fw-bold px-3 pt-3 text-secondary small">SYSTEM</div>
                <a href="{{ route('users.index') }}" class="list-group-item list-group-item-action">👥 Users</a>
                {{-- <a href="{{ route('settings.index') }}" class="list-group-item list-group-item-action">⚙ Settings</a> --}}
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="p-4" style="width: 100%;">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
