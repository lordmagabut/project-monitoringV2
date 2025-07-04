<div class="horizontal-menu">
  <nav class="navbar top-navbar">
    <div class="container">
      <div class="navbar-content">
        <a href="#" class="navbar-brand">
          Noble<span>UI</span>
        </a>
        <ul class="navbar-nav">       
          <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="me-2 icon-md" data-feather="user"></i> <!-- Icon -->
              <span>{{ auth()->user()->username }}</span> <!-- Nama User -->
          </a>

            <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
              <ul class="list-unstyled p-1">
                <li class="dropdown-item py-2">
                  <a href="javascript:;" class="text-body ms-0">
                  @if (auth()->user()->akses_user_manager == 1)
                      <li class="nav-item">
                          <a class="nav-link" href="{{ route('user.index') }}">User Manager</a>
                      </li>
                  @endif
                  </a>
                </li>
                <li class="dropdown-item py-2">
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link text-danger">
                        <i class="mdi mdi-logout"></i> Logout
                    </button>
                </form>
                </li>
              </ul>
            </div>
          </li>
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="horizontal-menu-toggle">
          <i data-feather="menu"></i>					
        </button>
      </div>
    </div>
  </nav>
  <nav class="bottom-navbar">
    <div class="container">
      <ul class="nav page-navigation">
        <li class="nav-item {{ active_class(['/']) }}">
          <a class="nav-link" href="{{ url('/') }}">
            <i class="link-icon" data-feather="box"></i>
            <span class="menu-title">Dashboard</span>
          </a>
        </li>
        <li class="nav-item {{ active_class(['email/*', 'apps/*']) }}">
          <a href="#" class="nav-link">
            <i class="link-icon" data-feather="folder"></i>
            <span class="menu-title">Daftar / List</span>
            <i class="link-arrow"></i>
          </a>
          <div class="submenu">
            <ul class="submenu-item">
            @if (auth()->user()->akses_perusahaan == 1)
                <li class="nav-item">
                    <a class="nav-link {{ active_class(['perusahaan*']) }}" href="{{ url('/perusahaan/') }}">Daftar Perusahaan</a>
                </li>
            @endif

            @if (auth()->user()->akses_supplier == 1) {{-- Jika ada hak akses supplier --}}
                <li class="nav-item">
                    <a class="nav-link {{ active_class(['supplier*']) }}" href="{{ url('/supplier/') }}">Daftar Supplier</a>
                </li>
            @endif

            @if (auth()->user()->akses_pemberikerja == 1)
                <li class="nav-item">
                    <a class="nav-link {{ active_class(['pemberiKerja*']) }}" href="{{ url('/pemberiKerja/') }}">Daftar Pemberi Kerja</a>
                </li>
            @endif

            @if (auth()->user()->akses_proyek == 1)
                <li class="nav-item">
                    <a class="nav-link {{ active_class(['proyek*']) }}" href="{{ url('/proyek/') }}">Daftar Proyek</a>
                </li>
            @endif

            @if (auth()->user()->akses_barang == 1)
                <li class="nav-item">
                    <a class="nav-link {{ active_class(['barang*']) }}" href="{{ url('/barang/') }}">Daftar Barang</a>
                </li>
            @endif

            @if (auth()->user()->akses_coa == 1)
                <li class="nav-item">
                    <a class="nav-link {{ active_class(['coa*']) }}" href="{{ url('/coa/') }}">Daftar Coa</a>
                </li>
            @endif

            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="link-icon" data-feather="pie-chart"></i>
            <span class="menu-title">Modul</span>
            <i class="link-arrow"></i>
          </a>
          <div class="submenu">
            <div class="row">
              <div class="col-md-6">
                <ul class="submenu-item pe-0">
                  <li class="category-heading">Pembelian</li>
                  <li class="nav-item"><a href="{{ url('/po/') }}" class="nav-link {{ active_class(['charts/apex']) }}">Pesanan Pembelian</a></li>
                </ul>
              </div>
              <div class="col-md-6">
                <ul class="submenu-item ps-0">
                  <li class="category-heading">Penjualan</li>
                  <li class="nav-item"><a href="{{ url('/so/') }}" class="nav-link {{ active_class(['tables/basic-tables']) }}">Pesanan Penjualan</a></li>
                </ul>
              </div>
            </div>
          </div>
        </li>
        <li class="nav-item">
          <a href="https://www.nobleui.com/laravel/documentation/docs.html" target="_blank" class="nav-link">
            <i class="link-icon" data-feather="hash"></i>
            <span class="menu-title">Documentation</span></a>
        </li>
      </ul>
    </div>
  </nav>
</div>