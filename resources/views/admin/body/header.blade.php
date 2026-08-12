<nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
    id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
            <i class="bx bx-menu bx-sm"></i>
        </a>
    </div>

    <div class="navbar-nav-right d-flex align-items-center w-100" id="navbar-collapse">
        <form action="{{ route('redirectSearch') }}" method="GET" class="xf-search">
            <i class="bx bx-search"></i>
            <input type="text" name="query" placeholder="Search products, invoices, customers..." value="{{ request('query') }}">
            <button type="submit" name="btn">Search</button>
        </form>

        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="user-chip">
                        <div class="avatar avatar-online">
                            <img src="{{ auth()->user()->photo ? asset('image/admin_images/' . auth()->user()->photo) : asset('image/blank_image.jpg') }}"
                                alt="User" class="w-px-40 h-auto rounded-circle" />
                        </div>
                        <div class="meta">
                            <strong>{{ Auth::user()->name }}</strong>
                            <small>{{ Auth::user()->role }}</small>
                        </div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <div class="avatar avatar-online">
                                        <img src="{{ auth()->user()->photo ? asset('image/admin_images/' . auth()->user()->photo) : asset('image/blank_image.jpg') }}"
                                             alt="User Avatar"
                                             class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold d-block">{{ Auth::user()->name }}</span>
                                    <small class="text-muted">{{ Auth::user()->role }}</small>
                                </div>
                            </div>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.profile') }}">
                            <i class="bx bx-user me-2"></i>
                            <span class="align-middle">My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.change.password') }}">
                            <i class="bx bx-key me-2"></i>
                            <span class="align-middle">Change Password</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider"></div>
                    </li>
                    <li>
                        <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                        <a class="dropdown-item" href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="bx bx-power-off me-2"></i>
                            <span class="align-middle">Log Out</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
