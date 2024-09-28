@extends('layout.main')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card shadow mb-4">
                    <!-- Card Header Anggota -->
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold" style="color: #08786B">Input Simpanan Anggota</h6>
                    </div>
                    <!-- Card Body Anggota -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="saving_product">Pilih Produk Simpanan</label>
                                    <select class="form-control select2 saving_product" style="width: 100%;"
                                        name="saving_product" id="saving_product">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="member_name">Pilih Nama Anggota*</label>
                                    <select class="form-control select2 member_name" style="width: 100%;" name="member_name"
                                        id="member_name">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Anggota</th>
                                            <th>Produk Simpanan</th>
                                            <th>Nominal Setoran</th>
                                            <th>Keterangan</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Total Setoran</th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>System Architect</td>
                                            <td>Edinburgh</td>
                                            <td>61</td>
                                            <td>2011/04/25</td>
                                            <td>$320,800</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary float-right ml-1">Simpan</button>
                        <button type="button" class="btn btn-success float-right">Cetak</button>
                        <a href="{{ route('home') }}" class="btn btn-danger float-left">Batal</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection
