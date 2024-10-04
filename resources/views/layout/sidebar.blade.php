@if (Route::currentRouteName() != 'login')
    <!-- Sidebar -->
    <ul class="navbar-nav sidebar sidebar-dark accordion d-none d-lg-block" style="background-color: #08786B"
        id="accordionSidebar">
        <!-- Sidebar - Brand -->
        <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
            BMT SARANA
        </a>

        <!-- Divider -->
        <hr class="sidebar-divider my-0" />

        <!-- Nav Item - Dashboard -->
        <li class="nav-item {{ Route::is('home') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-fw fa-tachometer-alt"></i>
                <span>Dashboard</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider" />

        <!-- Heading -->
        <div class="sidebar-heading">Master Data</div>

        <!-- Nav Item - Kumpulan Menu -->
        <li class="nav-item {{ Route::is('kumpulan') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kumpulan') }}">
                <i class="fas fa-users"></i>
                <span>Rembug/Area/Kumpulan</span></a>
        </li>

        <!-- Nav Item - Kumpulan Menu -->
        <li class="nav-item {{ Route::is('petugas') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('petugas') }}">
                <i class="fas fa-user-tie"></i>
                <span>Petugas</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider" />

        <!-- Heading -->
        <div class="sidebar-heading">Keanggotaan</div>

        <!-- Nav Item - Anggota Menu -->
        <li class="nav-item {{ Route::is('anggota') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('anggota') }}">
                <i class="fas fa-user-plus"></i>
                <span>Anggota</span></a>
        </li>

        <!-- Nav Item - Management User Menu -->
        <li class="nav-item {{ Route::is('management_user') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('management_user') }}">
                <i class="fas fa-user"></i>
                <span>Managemen User Anggota</span></a>
        </li>

        <!-- Nav Item - Produk Simpanan Menu -->
        <li class="nav-item {{ Route::is('simpanan') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('simpanan') }}">
                <i class="fas fa-wallet"></i>
                <span>Produk Simpanan</span></a>
        </li>

        <!-- Nav Item - Input Data Simpanan Menu -->
        <li class="nav-item {{ Route::is('input_simpanan') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('input_simpanan') }}">
                <i class="fas fa-cash-register"></i>
                <span>Input Data Simpanan</span></a>
        </li>

        <!-- Nav Item - Produk Pembiayaan Menu -->
        <li class="nav-item {{ Route::is('pembiayaan') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pembiayaan') }}">
                <i class="fa fa-money-bill"></i>
                <span>Produk Pembiayaan</span></a>
        </li>

        <!-- Nav Item - Input Data Pembiayaan Menu -->
        <li class="nav-item {{ Route::is('input_pembiayaan') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('input_pembiayaan') }}">
                <i class="fas fa-handshake"></i>
                <span>Input Data Pembiayaan</span></a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider" />

        <!-- Heading -->
        <div class="sidebar-heading">Transaksi Simpanan</div>

        <!-- Nav Item - Setor Kolektif -->
        <li class="nav-item {{ Route::is('input_simpanan_kolektif') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('input_simpanan_kolektif') }}">
                <i class="fas fa-plus-square"></i>
                <span>Setor Kolektif</span>
            </a>
        </li>

        <!-- Nav Item - Penarikan Kolektif -->
        <li class="nav-item {{ Route::is('penarikan_simpanan_kolektif') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('penarikan_simpanan_kolektif') }}">
                <i class="fas fa-minus-square"></i>
                <span>Penarikan Kolektif</span>
            </a>
        </li>

        <!-- Nav Item - Pindah Buku -->
        <li class="nav-item {{ Route::is('home') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-exchange-alt"></i>
                <span>Pindah Buku</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider" />

        <!-- Heading -->
        <div class="sidebar-heading">Transaksi Pembiayaan</div>

        <!-- Nav Item - Bayar Angsuran Kolektif -->
        <li class="nav-item {{ Route::is('input_pembiayaan_kolektif') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('input_pembiayaan_kolektif') }}">
                <i class="fas fa-receipt"></i>
                <span>Bayar Angsuran Kolektif</span>
            </a>
        </li>

        <!-- Nav Item - Cek -->
        <li class="nav-item {{ Route::is('home') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <i class="fas fa-tasks"></i>
                <span>Cek</span>
            </a>
        </li>

        <!-- Divider -->
        <hr class="sidebar-divider" />

        <!-- Heading -->
        <div class="sidebar-heading">Informasi Anggota</div>

        <!-- Nav Item - Bayar Angsuran Kolektif -->
        <li class="nav-item {{ Route::is('informasi_berita') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('informasi_berita') }}">
                <i class="fas fa-info"></i>
                <span>Informasi & Berita</span>
            </a>
        </li>

        <!-- Nav Item - Pesan Anggota -->
        <li class="nav-item {{ Route::is('pesan_anggota') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pesan_anggota') }}">
                <i class="fas fa-comment-dots"></i>
                <span>Pesan Anggota</span>
            </a>
        </li>
    </ul>
    <!-- End of Sidebar -->
@endif
