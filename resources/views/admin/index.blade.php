@extends('admin.admin_dashboard')


@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="premium-hero mb-4">
            <div>
                <div class="eyebrow">Operations overview</div>
                <h4>Welcome back, {{ Auth::user()->name }}</h4>
                <p>Track stock, purchases and invoices from one live workspace.</p>
            </div>
            <div class="hero-actions">
                <a href="{{ route('products.create') }}">New product</a>
                <a class="ghost" href="{{ route('invoices.create') }}">New invoice</a>
            </div>
        </div>

        <div class="demo-toolkit mb-4">
            <div class="demo-toolkit-copy">
                <div class="demo-toolkit-kicker">Sandbox</div>
                <h5>Demo data toolkit</h5>
                <p>
                    @if ($demoExists && $demoVisible)
                        Demo records are loaded and visible across suppliers, products, purchases and invoices.
                    @elseif ($demoExists && ! $demoVisible)
                        Demo records are still in the database, but hidden from the UI.
                    @else
                        Import sample inventory data to explore the full portal quickly.
                    @endif
                </p>
            </div>
            <div class="demo-toolkit-actions">
                <form action="{{ route('demo.import') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn demo-btn-import">
                        <i class="bx bx-download"></i>
                        {{ $demoExists && ! $demoVisible ? 'Show demo data' : 'Import demo data' }}
                    </button>
                </form>
                <form action="{{ route('demo.clear') }}" method="POST" onsubmit="return confirm('Hide demo data from UI only? Nothing will be deleted from the database.');">
                    @csrf
                    <button type="submit" class="btn demo-btn-clear" @disabled(! $demoExists || ! $demoVisible)>
                        <i class="bx bx-hide"></i>
                        Clear demo data
                    </button>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-6 col-lg-3 mb-4">
                <div class="card stat-card">
                    <div class="stat-icon violet"><i class="bx bx-package"></i></div>
                    <span>Products</span>
                    <h3>{{ number_format($stats['products']) }}</h3>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-4">
                <div class="card stat-card">
                    <div class="stat-icon cyan"><i class="bx bx-group"></i></div>
                    <span>Customers</span>
                    <h3>{{ number_format($stats['customers']) }}</h3>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-4">
                <div class="card stat-card">
                    <div class="stat-icon amber"><i class="bx bx-cart"></i></div>
                    <span>Purchases</span>
                    <h3>{{ number_format($stats['purchases']) }}</h3>
                </div>
            </div>
            <div class="col-6 col-lg-3 mb-4">
                <div class="card stat-card">
                    <div class="stat-icon pink"><i class="bx bx-receipt"></i></div>
                    <span>Invoices</span>
                    <h3>{{ number_format($stats['invoices']) }}</h3>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 mb-4 order-0">
                <div class="card">
                    <div class="d-flex align-items-end row">
                        <div class="col-sm-7">
                            <div class="card-body">
                                <h5 class="card-title text-primary">Inventory snapshot</h5>
                                <p class="mb-4">
                                    You currently manage <span class="fw-bold">{{ $stats['suppliers'] }}</span> suppliers
                                    and <span class="fw-bold">{{ $stats['categories'] }}</span> categories.
                                    Keep stock moving with faster purchases and invoices.
                                </p>
                                <a href="{{ route('stocks.report') }}" class="btn btn-sm btn-outline-primary">View stock report</a>
                            </div>
                        </div>
                        <div class="col-sm-5 text-center text-sm-left">
                            <div class="card-body pb-0 px-0 px-md-4">
                                <img src="{{asset('backend/assets/img/illustrations/man-with-laptop-light.png')}}" height="140"
                                    alt="Inventory overview" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 order-1">
                <div class="row">
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{asset('backend/assets/img/icons/unicons/chart-success.png')}}" alt="chart success"
                                            class="rounded" />
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">Suppliers</span>
                                <h3 class="card-title mb-2">{{ number_format($stats['suppliers']) }}</h3>
                                <small class="text-success fw-semibold">Active partners</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{asset('backend/assets/img/icons/unicons/wallet-info.png')}}" alt="Categories"
                                            class="rounded" />
                                    </div>
                                </div>
                                <span>Categories</span>
                                <h3 class="card-title text-nowrap mb-1">{{ number_format($stats['categories']) }}</h3>
                                <small class="text-success fw-semibold">Catalog groups</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Total Revenue -->
            <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
                <div class="card">
                    <div class="row row-bordered g-0">
                        <div class="col-md-8">
                            <h5 class="card-header m-0 me-2 pb-3">Total Revenue</h5>
                            <div class="chart-shell" style="--chart-h: 300px">
                                <div class="chart-skeleton" aria-hidden="true"></div>
                                <div id="totalRevenueChart" class="px-2"></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-body">
                                <div class="text-center">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button"
                                            id="growthReportId" data-bs-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            2022
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId">
                                            <a class="dropdown-item" href="javascript:void(0);">2021</a>
                                            <a class="dropdown-item" href="javascript:void(0);">2020</a>
                                            <a class="dropdown-item" href="javascript:void(0);">2019</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="chart-shell" style="--chart-h: 240px">
                                <div class="chart-skeleton gauge" aria-hidden="true"></div>
                                <div id="growthChart"></div>
                            </div>
                            <div class="text-center fw-semibold pt-3 mb-2">62% Company Growth</div>

                            <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                                <div class="d-flex">
                                    <div class="me-2">
                                        <span class="badge bg-label-primary p-2"><i
                                                class="bx bx-dollar text-primary"></i></span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small>2022</small>
                                        <h6 class="mb-0">{{ currency_symbol() }}32.5k</h6>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="me-2">
                                        <span class="badge bg-label-info p-2"><i
                                                class="bx bx-wallet text-info"></i></span>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <small>2021</small>
                                        <h6 class="mb-0">{{ currency_symbol() }}41.2k</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Total Revenue -->
            <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                <div class="row">
                    <div class="col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{asset('backend/assets/img/icons/unicons/paypal.png')}}" alt="Credit Card"
                                            class="rounded" />
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="cardOpt4" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                            <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="d-block mb-1">Purchases</span>
                                <h3 class="card-title text-nowrap mb-2">{{ number_format($stats['purchases']) }}</h3>
                                <small class="text-success fw-semibold">Incoming stock</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title d-flex align-items-start justify-content-between">
                                    <div class="avatar flex-shrink-0">
                                        <img src="{{asset('backend/assets/img/icons/unicons/cc-primary.png')}}" alt="Credit Card"
                                            class="rounded" />
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="cardOpt1">
                                            <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                            <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                                        </div>
                                    </div>
                                </div>
                                <span class="fw-semibold d-block mb-1">Invoices</span>
                                <h3 class="card-title mb-2">{{ number_format($stats['invoices']) }}</h3>
                                <small class="text-success fw-semibold">Sales documents</small>
                            </div>
                        </div>
                    </div>
                    <!-- </div>
    <div class="row"> -->
                    <div class="col-12 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                                    <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                                        <div class="card-title">
                                            <h5 class="text-nowrap mb-2">Profile Report</h5>
                                            <span class="badge bg-label-warning rounded-pill">Year 2021</span>
                                        </div>
                                        <div class="mt-sm-auto">
                                            <small class="text-success text-nowrap fw-semibold"><i
                                                    class="bx bx-chevron-up"></i> 68.2%</small>
                                            <h3 class="mb-0">{{ currency_symbol() }}84,686k</h3>
                                        </div>
                                    </div>
                                    <div class="chart-shell" style="--chart-h: 80px">
                                        <div class="chart-skeleton line" aria-hidden="true"></div>
                                        <div id="profileReportChart"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Order Statistics -->
            <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                        <div class="card-title mb-0">
                            <h5 class="m-0 me-2">Order Statistics</h5>
                            <small class="text-muted">42.82k Total Sales</small>
                        </div>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="orederStatistics" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
                                <a class="dropdown-item" href="javascript:void(0);">Select All</a>
                                <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                                <a class="dropdown-item" href="javascript:void(0);">Share</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex flex-column align-items-center gap-1">
                                <h2 class="mb-2">8,258</h2>
                                <span>Total Orders</span>
                            </div>
                            <div class="chart-shell" style="--chart-h: 165px">
                                <div class="chart-skeleton donut" aria-hidden="true"></div>
                                <div id="orderStatisticsChart"></div>
                            </div>
                        </div>
                        <ul class="p-0 m-0">
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i
                                            class="bx bx-mobile-alt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Electronic</h6>
                                        <small class="text-muted">Mobile, Earbuds, TV</small>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold">82.5k</small>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-success"><i
                                            class="bx bx-closet"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Fashion</h6>
                                        <small class="text-muted">T-shirt, Jeans, Shoes</small>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold">23.8k</small>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-info"><i
                                            class="bx bx-home-alt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Decor</h6>
                                        <small class="text-muted">Fine Art, Dining</small>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold">849k</small>
                                    </div>
                                </div>
                            </li>
                            <li class="d-flex">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-secondary"><i
                                            class="bx bx-football"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <h6 class="mb-0">Sports</h6>
                                        <small class="text-muted">Football, Cricket Kit</small>
                                    </div>
                                    <div class="user-progress">
                                        <small class="fw-semibold">99</small>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Order Statistics -->

            <!-- Expense Overview -->
            <div class="col-md-6 col-lg-4 order-1 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <ul class="nav nav-pills" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-tabs-line-card-income"
                                    aria-controls="navs-tabs-line-card-income" aria-selected="true">
                                    Income
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab">Expenses</button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab">Profit</button>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body px-0">
                        <div class="tab-content p-0">
                            <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                                <div class="d-flex p-4 pt-3">
                                    <div class="avatar flex-shrink-0 me-3">
                                        <img src="{{asset('backend/assets/img/icons/unicons/wallet.png')}}" alt="User" />
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Total Balance</small>
                                        <div class="d-flex align-items-center">
                                            <h6 class="mb-0 me-1">{{ currency_symbol() }}459.10</h6>
                                            <small class="text-success fw-semibold">
                                                <i class="bx bx-chevron-up"></i>
                                                42.9%
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="chart-shell" style="--chart-h: 215px">
                                    <div class="chart-skeleton" aria-hidden="true"></div>
                                    <div id="incomeChart"></div>
                                </div>
                                <div class="d-flex justify-content-center pt-4 gap-2">
                                    <div class="flex-shrink-0">
                                        <div class="chart-shell" style="--chart-h: 60px">
                                            <div class="chart-skeleton gauge" aria-hidden="true"></div>
                                            <div id="expensesOfWeek"></div>
                                        </div>
                                    </div>
                                    <div>
                                        <p class="mb-n1 mt-1">Expenses This Week</p>
                                        <small class="text-muted">{{ currency_symbol() }}39 less than last week</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ Expense Overview -->

            <!-- Transactions -->
            <div class="col-md-6 col-lg-4 order-2 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 me-2">Quick actions</h5>
                        <div class="dropdown">
                            <button class="btn p-0" type="button" id="transactionID" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="bx bx-dots-vertical-rounded"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
                                <a class="dropdown-item" href="javascript:void(0);">Last 28 Days</a>
                                <a class="dropdown-item" href="javascript:void(0);">Last Month</a>
                                <a class="dropdown-item" href="javascript:void(0);">Last Year</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="p-0 m-0">
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary"><i class="bx bx-package"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <small class="text-muted d-block mb-1">Catalog</small>
                                        <h6 class="mb-0">Manage products</h6>
                                    </div>
                                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary">Open</a>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-success"><i class="bx bx-cart"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <small class="text-muted d-block mb-1">Incoming</small>
                                        <h6 class="mb-0">Create purchase</h6>
                                    </div>
                                    <a href="{{ route('purchases.create') }}" class="btn btn-sm btn-outline-primary">Open</a>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-info"><i class="bx bx-receipt"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <small class="text-muted d-block mb-1">Sales</small>
                                        <h6 class="mb-0">Create invoice</h6>
                                    </div>
                                    <a href="{{ route('invoices.create') }}" class="btn btn-sm btn-outline-primary">Open</a>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-warning"><i class="bx bx-box"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <small class="text-muted d-block mb-1">Warehouse</small>
                                        <h6 class="mb-0">Stock report</h6>
                                    </div>
                                    <a href="{{ route('stocks.report') }}" class="btn btn-sm btn-outline-primary">Open</a>
                                </div>
                            </li>
                            <li class="d-flex mb-4 pb-1">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-secondary"><i class="bx bx-group"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <small class="text-muted d-block mb-1">People</small>
                                        <h6 class="mb-0">Customers</h6>
                                    </div>
                                    <a href="{{ route('customers.index') }}" class="btn btn-sm btn-outline-primary">Open</a>
                                </div>
                            </li>
                            <li class="d-flex">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-danger"><i class="bx bx-check-shield"></i></span>
                                </div>
                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                    <div class="me-2">
                                        <small class="text-muted d-block mb-1">Approvals</small>
                                        <h6 class="mb-0">Pending invoices</h6>
                                    </div>
                                    <a href="{{ route('invoices.approvalPage') }}" class="btn btn-sm btn-outline-primary">Open</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <!--/ Transactions -->
        </div>
    </div>
@endsection
