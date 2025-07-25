<div class="horizontal-menu">
  <nav class="navbar top-navbar">
    <div class="container">
      <div class="navbar-content">
        <a href="#" class="navbar-brand">
          @Nanda<span>Purwanda</span>
        </a>
        <ul class="navbar-nav">       
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="me-2 icon-md" data-feather="user"></i>
              <span>{{ auth()->user()->username }}</span>
            </a>
            <div class="dropdown-menu p-0" aria-labelledby="profileDropdown">
              <ul class="list-unstyled p-1">
                @if (auth()->user()->akses_user_manager == 1)
                <li class="dropdown-item py-2">
                  <a href="{{ route('user.index') }}" class="text-body ms-0">User Manager</a>
                </li>
                @endif
                <li class="dropdown-item py-2">
                  <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-icon-text">
                    <i class="btn-icon-prepend" data-feather="log-out"></i>
                    <span>Log Out</span>
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

        <li class="nav-item mega-menu">
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

              @if (auth()->user()->akses_supplier == 1)
              <li class="nav-item">
                <a class="nav-link {{ active_class(['supplier*']) }}" href="{{ url('/supplier/') }}">Daftar Supplier</a>
              </li>
              @endif

              @if (auth()->user()->akses_pemberikerja == 1)
              <li class="nav-item">
                <a class="nav-link {{ active_class(['pemberiKerja*']) }}" href="{{ url('/pemberiKerja/') }}">Daftar Pemberi Kerja</a>
              </li>
              @endif

              @if (auth()->user()->akses_barang == 1)
              <li class="nav-item">
                <a class="nav-link {{ active_class(['barang*']) }}" href="{{ url('/barang/') }}">Daftar Barang</a>
              </li>
              @endif

              @if (auth()->user()->akses_coa == 1)
              <li class="nav-item">
                <a class="nav-link {{ active_class(['coa*']) }}" href="{{ url('/coa/') }}">Daftar COA</a>
              </li>
              @endif
            </ul>
          </div>
        </li>

        <li class="nav-item mega-menu">
          <a href="#" class="nav-link">
            <i class="link-icon" data-feather="pie-chart"></i>
            <span class="menu-title">Modul</span>
            <i class="link-arrow"></i>
          </a>
          <div class="submenu">
            <div class="col-group-wrapper row">
               <div class="col-md-3">
                <p class="category-heading">Pembelian</p>
                <ul class="submenu-item pe-0">
                @if (auth()->user()->akses_po == 1)
                  <li class="nav-item">
                    <a href="{{ url('/po/') }}" class="nav-link">Pesanan Pembelian</a>
                  </li>
                  @endif
                  @if (auth()->user()->akses_faktur == 1)
                  <li class="nav-item">
                    <a href="{{ url('/faktur/') }}" class="nav-link">Faktur Pembelian</a>
                  </li>
                  @endif
                </ul>
              </div>
              <div class="col-md-3">
                <p class="category-heading">Penjualan</p>
                <ul class="submenu-item pe-0">
                  <li class="nav-item">
                    <a href="{{ url('/so/') }}" class="nav-link">Pesanan Penjualan</a>
                  </li>

                </ul>
              </div>
              <div class="col-md-3">
                <p class="category-heading">Proyek</p>
                <ul class="submenu-item pe-0">
                  @if (auth()->user()->akses_proyek == 1)
                    <li class="nav-item">
                      <a class="nav-link" href="{{ url('/proyek/') }}">Daftar Proyek</a>
                    </li>
                  @endif
                  @if (auth()->user()->akses_proyek == 1)
                    <li class="nav-item">
                      <a class="nav-link" href="{{ url('/ahsp/') }}">Analisa Harga Satuan Pekerjaan</a>
                    </li>
                  @endif
                </ul>
             </div>   
            </div>
          </div>
        </li>
     
        <li class="nav-item mega-menu">
          <a href="#" class="nav-link">
            <i class="link-icon" data-feather="book"></i>
            <span class="menu-title">Laporan</span>
            <i class="link-arrow"></i>
          </a>
          <div class="submenu">
            <div class="col-group-wrapper row">
            <div class="col-group col-md-3">
                <p class="category-heading">Laporan</p>
                <ul class="submenu-item pe-0">
                @if (auth()->user()->akses_jurnal == 1)
                  <li class="nav-item">
                    <a href="{{ url('/jurnal/') }}" class="nav-link">Jurnal</a>
                  </li>
                @endif
                  <li class="nav-item">
                    <a href="{{ url('/buku-besar/') }}" class="nav-link">Buku Besar</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ route('laporan.neraca') }}">Neraca</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{ route('laporan.labaRugi') }}" class="nav-link">Laba Rugi</a>
                  </li>
                </ul>
              </div>
              <div class="col-group col-md-3">
                <p class="category-heading">Auth Pages</p>
                <ul class="submenu-item">
                  <li class="nav-item"><a href="{{ url('/auth/login') }}" class="nav-link {{ active_class(['auth/login']) }}">Login</a></li>
                  <li class="nav-item"><a href="{{ url('/auth/register') }}" class="nav-link {{ active_class(['auth/register']) }}">Register</a></li>
                </ul>
              </div>
              <div class="col-group col-md-3">
                <p class="category-heading">Error Pages</p>
                <ul class="submenu-item">
                  <li class="nav-item"><a href="{{ url('/error/404') }}" class="nav-link {{ active_class(['error/404']) }}">404</a></li>
                  <li class="nav-item"><a href="{{ url('/error/500') }}" class="nav-link {{ active_class(['error/500']) }}">500</a></li>
                </ul>
              </div>
            </div>
          </div>
        </li>
        <li class="nav-item">
          <a href="https://www.nobleui.com/laravel/documentation/docs.html" target="_blank" class="nav-link">
            <i class="link-icon" data-feather="hash"></i>
            <span class="menu-title">Documentation</span>
          </a>
        </li>

      </ul>
    </div>
  </nav>
</div>
