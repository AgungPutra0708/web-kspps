@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="container d-lg-none">
                <!-- Page Heading -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Keanggotaan</h1>
                </div>

                <!-- Content Row -->
                <div class="row">
                    <!-- Card Anggota -->
                    <div class="col-xl-4 col-md-6 col-sm-6 mb-4">
                        <a href="{{ route('kumpulan') }}" class="nav-link">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                                Kumpulan
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card Anggota -->
                    <div class="col-xl-4 col-md-6 col-sm-6 mb-4">
                        <a href="{{ route('anggota') }}" class="nav-link">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                                Anggota
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Produk Simpanan Card -->
                    <div class="col-xl-4 col-md-6 col-sm-6 mb-4">
                        <a href="{{ route('simpanan') }}" class="nav-link">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                                Produk Simpanan
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-wallet fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Produk Pembiayaan Card -->
                    <div class="col-xl-4 col-md-6 col-sm-6 mb-4">
                        <a href="{{ route('pembiayaan') }}" class="nav-link">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                                Produk Pembiayaan
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fa fa-money-bill fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Manajemen User Card -->
                    <div class="col-xl-4 col-md-6 col-sm-6 mb-4">
                        <a href="{{ route('management_user') }}" class="nav-link">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                                Manajemen User Anggota
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-user fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!-- Input Data Simpanan Card -->
                    <div class="col-xl-4 col-md-6 col-sm-6 mb-4">
                        <a href="{{ route('input_simpanan') }}" class="nav-link">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                                Input Data Simpanan
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-cash-register fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Input Data Pembiayaan Card -->
                    <div class="col-xl-4 col-md-6 col-sm-6 mb-4">
                        <a href="{{ route('input_pembiayaan') }}" class="nav-link">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                                Input Data Pembiayaan
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-handshake fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Transaksi Simpanan -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Transaksi Simpanan</h1>
                </div>

                <!-- Content Row -->
                <div class="row">
                    <!-- Setor Kolektif Anggota -->
                    <div class="col-xl-4 col-md-6 col-sm-6 mb-4">
                        <a href="{{ route('input_simpanan_kolektif') }}" class="nav-link">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                                Setor Kolektif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-plus-square fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Penarikan Kolektif Card -->
                    <div class="col-xl-4 col-md-6 col-sm-6 mb-4">
                        <a href="{{ route('penarikan_simpanan_kolektif') }}" class="nav-link">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                                Penarikan Kolektif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-minus-square fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Pindah Buku Card -->
                    <div class="col-xl-4 col-md-6 col-sm-6 mb-4">
                        <a href="" class="nav-link">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                                Pindah Buku
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-exchange-alt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Transaksi Pembiayaan -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Transaksi Pembiayaan</h1>
                </div>

                <!-- Content Row -->
                <div class="row">
                    <!-- Bayar Angsuran Kolektif Anggota -->
                    <div class="col-xl-4 col-md-6 col-sm-6 mb-4">
                        <a href="{{ route('input_pembiayaan_kolektif') }}" class="nav-link">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                                Bayar Angsuran Kolektif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Cek Pembiayaan Card -->
                    <div class="col-xl-4 col-md-6 col-sm-6 mb-4">
                        <a href="" class="nav-link">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                                Cek
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-tasks fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Transaksi Pembiayaan -->
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Informasi Anggota</h1>
                </div>

                <!-- Content Row -->
                <div class="row">
                    <!-- Bayar Angsuran Kolektif Anggota -->
                    <div class="col-xl-4 col-md-6 col-sm-6 mb-4">
                        <a href="{{ route('informasi_berita') }}" class="nav-link">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                                Informasi & Berita
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-info fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Cek Pembiayaan Card -->
                    <div class="col-xl-4 col-md-6 col-sm-6 mb-4">
                        <a href="{{ route('pesan_anggota') }}" class="nav-link">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="h4 mb-0 font-weight-bold text-gray-800">
                                                Pesan Anggota
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-comment-dots fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </div>
    <!-- End of Main Content -->
@endsection
