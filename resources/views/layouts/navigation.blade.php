<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow-sm py-2">
    <div class="container">
        <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
            <span class="badge bg-primary p-2 rounded-3"><i class="bi bi-box-seam fs-5"></i></span>
            <span>Inventory System <small class="text-info fs-6 fw-normal">Laravel</small></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-semibold' : '' }}" href="{{ route('dashboard') }}">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('products.*') ? 'active fw-semibold' : '' }}" href="{{ route('products.index') }}">
                        <i class="bi bi-boxes me-1"></i> Produk
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('categories.*') ? 'active fw-semibold' : '' }}" href="{{ route('categories.index') }}">
                        <i class="bi bi-tags me-1"></i> Kategori
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('suppliers.*') ? 'active fw-semibold' : '' }}" href="{{ route('suppliers.index') }}">
                        <i class="bi bi-truck me-1"></i> Supplier
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('stock-movements.*') ? 'active fw-semibold' : '' }}" href="{{ route('stock-movements.index') }}">
                        <i class="bi bi-arrow-left-right me-1"></i> Mutasi Stok
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center">
                @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-light d-flex align-items-center gap-2" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5 text-primary"></i>
                            <span>{{ Auth::user()->name }}</span>
                            <span class="badge {{ Auth::user()->isAdmin() ? 'bg-danger' : 'bg-secondary' }}">
                                {{ strtoupper(Auth::user()->role) }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="userMenu">
                            <li><h6 class="dropdown-header">Akun: {{ Auth::user()->username }}</h6></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="btn btn-outline-light btn-sm px-3" href="{{ route('login') }}">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
