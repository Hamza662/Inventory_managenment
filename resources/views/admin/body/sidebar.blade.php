<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
    <div class="app-brand demo">
        <a href="{{ route('admin.index') }}" class="app-brand-link">
            <span class="brand-mark me-2">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M3 7.5L12 3l9 4.5v9L12 21l-9-4.5v-9z" stroke="white" stroke-width="1.8"/>
                    <path d="M12 12l9-4.5M12 12v9M12 12L3 7.5" stroke="white" stroke-width="1.8"/>
                </svg>
            </span>
            <span class="app-brand-text demo menu-text fw-bolder">Inventory</span>
        </a>

        <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
        </a>
    </div>

    <div class="menu-inner-shadow"></div>

    <ul class="menu-inner py-1">
        <li class="menu-item {{ request()->routeIs('admin.index') ? 'active' : '' }}">
            <a href="{{ route('admin.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Dashboard</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">People</span></li>

        <li class="menu-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
            <a href="{{ route('suppliers.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-store"></i>
                <div>Suppliers</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('customers.*') || request()->routeIs('creditcustomers.*') ? 'active' : '' }}">
            <a href="{{ route('customers.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-group"></i>
                <div>Customers</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Catalog</span></li>

        <li class="menu-item {{ request()->routeIs('units.*') ? 'active' : '' }}">
            <a href="{{ route('units.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-ruler"></i>
                <div>Units</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <a href="{{ route('categories.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-purchase-tag"></i>
                <div>Categories</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
            <a href="{{ route('products.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-package"></i>
                <div>Products</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Operations</span></li>

        <li class="menu-item {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
            <a href="{{ route('purchases.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-cart"></i>
                <div>Purchases</div>
            </a>
        </li>

        <li class="menu-item {{ request()->routeIs('invoices.*') ? 'active' : '' }}">
            <a href="{{ route('invoices.index') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-receipt"></i>
                <div>Invoices</div>
            </a>
        </li>

        <li class="menu-header small text-uppercase"><span class="menu-header-text">Stock</span></li>

        <li class="menu-item {{ request()->routeIs('stocks.*') || request()->routeIs('reports.*') ? 'active' : '' }}">
            <a href="{{ route('stocks.report') }}" class="menu-link">
                <i class="menu-icon tf-icons bx bx-box"></i>
                <div>Stock</div>
            </a>
        </li>
    </ul>
</aside>
