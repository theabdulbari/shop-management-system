<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ?? 'ERP System' }}</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet"> --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select/dist/css/bootstrap-select.min.css"> --}}
    <style>
        .sidebar {
            position: fixed;
            top: 56px;               /* navbar height */
            left: 0;
            /* width: 250px; */
            height: calc(100vh - 56px);
            overflow-y: auto;
            z-index: 1000;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            padding-top: 76px; /* navbar space */
        }

    </style>
    @livewireStyles
</head>

<body>
    <!-- TOP NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-3 sticky-top">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <img style="width: 24px;" src="{{ $appSetting?->logo 
                ? asset('storage/'.$appSetting->logo) 
                : asset('images/erp_logo.png').'?v='.time() }}" alt="Logo" class="me-1">
            {{ $appSetting->system_name ?? 'ERP' }} 
        </a>

        


        <div class="ms-auto text-white">
            <div class="d-flex justify-content-between">
                <div class="me-3 mt-2">
                    <div class="dropdown me-3">
                        <a class="text-white position-relative"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                        style="text-decoration:none;"
                        >
                            🔔
                            @if($lowStockProducts->count())
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $lowStockProducts->count() }}
                                </span>
                            @endif
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow"
                            style="width: 320px; max-height: 350px; overflow-y:auto;"
                        >
                            <li class="dropdown-header fw-bold text-danger">
                                ⚠ Low Stock Items
                            </li>

                            @forelse($lowStockProducts as $product)
                                <li>
                                    <a class="dropdown-item d-flex justify-content-between align-items-center"
                                    href="{{ route('products.edit', $product->id) }}"
                                    >
                                        <div>
                                            <div class="fw-semibold">{{ $product->name }}</div>
                                            <small class="text-muted">
                                                Stock: {{ $product->stock_qty }}
                                            </small>
                                        </div>

                                        <span class="badge bg-warning text-dark">
                                            Low
                                        </span>
                                    </a>
                                </li>
                            @empty
                                <li class="dropdown-item text-muted text-center">
                                    ✅ No low stock items
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
                <div>
                    {{ Auth::user()->name }}
                    <a class="btn btn-sm btn-danger ms-3" href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
                </div>   
            </div> 
        </div>
    </nav>

    <div class="d-flex">
        <!-- SIDEBAR -->
        <div class="sidebar bg-light border-end" style="width: 250px; min-height: 100vh;">
            <div class="list-group list-group-flush">

                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action">📊 Dashboard</a>

                {{-- <div class="fw-bold px-3 pt-3 text-secondary small">CUSTOMERS</div> --}}
                <a href="{{ route('customers.index') }}" class="list-group-item list-group-item-action">👥 Customers</a>
                <a href="{{ route('expenses.index') }}" class="list-group-item list-group-item-action">💵 Expense</a>
                <a href="{{ route('loans.index') }}" class="list-group-item list-group-item-action">💰 Loans</a>

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

                <div class="fw-bold px-3 pt-3 text-secondary small">SETTINGS</div>
                <a href="{{ route('settings') }}" class="list-group-item list-group-item-action">⚙️ System Settings</a>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="p-4 main-content" style="width: 100%;">
            {{ $slot }}
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select/dist/js/bootstrap-select.min.js"></script> --}}

    <script>
        // function initSmsSelect2() {
        //     $('.sms-select2').each(function () {
        //         if (!$(this).hasClass("select2-hidden-accessible")) {
        //             $(this).select2({
        //                 width: '100%'
        //             });
        //         }
        //     });
        // }

        // document.addEventListener('livewire:init', () => {
        //     initSmsSelect2();

        //     Livewire.hook('message.processed', () => {
        //         initSmsSelect2();
        //     });
        // });

    </script>

    <script>
    // document.addEventListener('livewire:init', () => {

    //     $(document).on('change', '.sms-select2', function () {

    //         const value = $(this).val();
    //         const name  = $(this).attr('name');

    //         // Get active Livewire component
    //         const component = Livewire.find(
    //             $(this).closest('[wire\\:id]').attr('wire:id')
    //         );

    //         if (component) {
    //             component.set(name, value);
    //         }

    //         console.log('Select2 changed:', name, value);
    //     });

    // });
    </script>

</body>
</html>
