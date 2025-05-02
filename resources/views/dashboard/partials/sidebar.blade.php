<div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
        aria-hidden="true" id="iconSidenav"></i>
    <a class="navbar-brand m-0" href=" https://demos.creative-tim.com/argon-dashboard/pages/dashboard.html "
        target="_blank">
        <img src="../assets/img/logo-ct-dark.png" width="26px" height="26px" class="navbar-brand-img h-100"
            alt="main_logo">
        <span class="ms-1 font-weight-bold">Prediksi Harga Beras</span>
    </a>
</div>
<hr class="horizontal dark mt-0">
<div class="collapse navbar-collapse w-auto " id="sidenav-collapse-main">
    <ul class="navbar-nav">

        <li class="nav-item">
            <a class="nav-link {{ Request::is('dashboard') ? 'active' : '' }}" href="/dashboard">
                <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ Request::is('dashboard/prediksi*') ? 'active' : '' }}" href="/dashboard/prediksi">
                <div
                    class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="ni ni-world-2 text-dark text-sm opacity-10"></i>
                </div>
                <span class="nav-link-text ms-1">Prediksi</span>
            </a>
        </li>

        @if (auth()->user()->isAdmin === 1)
            <li class="nav-item">
                <a class="nav-link {{ Request::is('dashboard/laporan*') ? 'active' : '' }}" href="/dashboard/laporan">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-copy-04 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Laporan</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Request::is('dashboard/data*') ? 'active' : '' }}" href="/dashboard/data-aktual">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data Aktual</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ Request::is('dashboard/user*') ? 'active' : '' }}" href="/dashboard/user">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">User</span>
                </a>
            </li>
        @endif
        <li class="nav-item mt-auto">
            <form action="{{ route('logout') }}" method="POST" class="d-flex justify-content-center">
                @csrf
                <button type="submit" class="btn btn-warning btn-sm font-weight-bold">
                    <i class="fa fa-user me-sm-1"></i>
                    <span class="d-sm-inline d-none">Logout</span>
                </button>
            </form>
        </li>

    </ul>
</div>
