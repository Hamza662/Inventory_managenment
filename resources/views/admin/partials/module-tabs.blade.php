@php
    $modules = [
        [
            'match' => ['suppliers.*'],
            'title' => 'Suppliers',
            'tabs' => [
                ['label' => 'All', 'route' => 'suppliers.index', 'icon' => 'bx-list-ul'],
                ['label' => 'Create', 'route' => 'suppliers.create', 'icon' => 'bx-plus'],
                ['label' => 'Trash', 'route' => 'suppliers.trash', 'icon' => 'bx-trash'],
            ],
        ],
        [
            'match' => ['customers.*', 'creditcustomers.*'],
            'title' => 'Customers',
            'tabs' => [
                ['label' => 'All', 'route' => 'customers.index', 'icon' => 'bx-list-ul'],
                ['label' => 'Create', 'route' => 'customers.create', 'icon' => 'bx-plus'],
                ['label' => 'Credit', 'route' => 'creditcustomers.index', 'icon' => 'bx-credit-card', 'match' => 'creditcustomers.*'],
                ['label' => 'Trash', 'route' => 'customers.trash', 'icon' => 'bx-trash'],
            ],
        ],
        [
            'match' => ['units.*'],
            'title' => 'Units',
            'tabs' => [
                ['label' => 'All', 'route' => 'units.index', 'icon' => 'bx-list-ul'],
                ['label' => 'Create', 'route' => 'units.create', 'icon' => 'bx-plus'],
            ],
        ],
        [
            'match' => ['categories.*'],
            'title' => 'Categories',
            'tabs' => [
                ['label' => 'All', 'route' => 'categories.index', 'icon' => 'bx-list-ul'],
                ['label' => 'Create', 'route' => 'categories.create', 'icon' => 'bx-plus'],
                ['label' => 'Trash', 'route' => 'categories.trash', 'icon' => 'bx-trash'],
            ],
        ],
        [
            'match' => ['products.*'],
            'title' => 'Products',
            'tabs' => [
                ['label' => 'All', 'route' => 'products.index', 'icon' => 'bx-list-ul'],
                ['label' => 'Create', 'route' => 'products.create', 'icon' => 'bx-plus'],
                ['label' => 'Trash', 'route' => 'products.trash', 'icon' => 'bx-trash'],
            ],
        ],
        [
            'match' => ['purchases.*'],
            'title' => 'Purchases',
            'tabs' => [
                ['label' => 'All', 'route' => 'purchases.index', 'icon' => 'bx-list-ul'],
                ['label' => 'Create', 'route' => 'purchases.create', 'icon' => 'bx-plus'],
                ['label' => 'Approve', 'route' => 'purchases.approvalPage', 'icon' => 'bx-check-shield'],
                ['label' => 'Daily report', 'route' => 'purchases.generateReport', 'icon' => 'bx-bar-chart-alt-2'],
                ['label' => 'Trash', 'route' => 'purchases.trash', 'icon' => 'bx-trash'],
            ],
        ],
        [
            'match' => ['invoices.*'],
            'title' => 'Invoices',
            'tabs' => [
                ['label' => 'All', 'route' => 'invoices.index', 'icon' => 'bx-list-ul'],
                ['label' => 'Create', 'route' => 'invoices.create', 'icon' => 'bx-plus'],
                ['label' => 'Approve', 'route' => 'invoices.approvalPage', 'icon' => 'bx-check-shield'],
                ['label' => 'Daily report', 'route' => 'invoices.generateReport', 'icon' => 'bx-bar-chart-alt-2'],
                ['label' => 'Trash', 'route' => 'invoices.trash', 'icon' => 'bx-trash'],
            ],
        ],
        [
            'match' => ['stocks.*', 'reports.*'],
            'title' => 'Stock',
            'tabs' => [
                ['label' => 'Stock report', 'route' => 'stocks.report', 'icon' => 'bx-box'],
                ['label' => 'Supplier / product', 'route' => 'reports.supplier_product', 'icon' => 'bx-git-compare'],
            ],
        ],
        [
            'match' => ['roles.*'],
            'title' => 'Roles',
            'tabs' => [
                ['label' => 'All', 'route' => 'roles.index', 'icon' => 'bx-list-ul'],
                ['label' => 'Create', 'route' => 'roles.create', 'icon' => 'bx-plus'],
                ['label' => 'Trash', 'route' => 'roles.trash', 'icon' => 'bx-trash'],
            ],
        ],
        [
            'match' => ['users.*'],
            'title' => 'Users',
            'tabs' => [
                ['label' => 'All', 'route' => 'users.index', 'icon' => 'bx-list-ul'],
                ['label' => 'Create', 'route' => 'users.create', 'icon' => 'bx-plus'],
                ['label' => 'Trash', 'route' => 'users.trash', 'icon' => 'bx-trash'],
            ],
        ],
    ];

    $current = collect($modules)->first(function ($module) {
        return collect($module['match'])->contains(fn ($pattern) => request()->routeIs($pattern));
    });

    if ($current && request()->routeIs(
        'invoices.print',
        'invoices.generatePdf',
        'purchases.generatePdf',
        'download_pdf',
        'credit_customer_print'
    )) {
        $current = null;
    }
@endphp

@if ($current)
    <div class="module-tabs-wrap">
        <div>
            <div class="module-kicker">Module</div>
            <h3 class="module-title">{{ $current['title'] }}</h3>
        </div>
        <div class="module-tabs">
            @foreach ($current['tabs'] as $tab)
                @php
                    $isActive = request()->routeIs($tab['match'] ?? $tab['route']);
                @endphp
                <a href="{{ route($tab['route']) }}" class="module-tab {{ $isActive ? 'active' : '' }}">
                    <i class="bx {{ $tab['icon'] }}"></i>
                    {{ $tab['label'] }}
                </a>
            @endforeach
        </div>
    </div>
@endif
