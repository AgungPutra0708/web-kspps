@extends('layout.main')

@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="card shadow mb-4">
                    <!-- Card Header Anggota -->
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold" style="color: #08786B">Manajemen User Anggota</h6>
                    </div>
                    <!-- Card Body Anggota -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="member_number">Nomor Anggota*</label>
                                    <input type="text" class="form-control member_number" name="member_number"
                                        id="member_number" placeholder="Nomor Anggota" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="member_name">Nama Anggota*</label>
                                    <input type="text" class="form-control member_name" name="member_name"
                                        id="member_name" placeholder="Nama Anggota">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="member_group">Rembug/Area/Kelompok*</label>
                                    <select class="form-control select2 member_group" style="width: 100%;"
                                        name="member_group" id="member_group">
                                        <option></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="member_username">Username Anggota</label>
                                    <input type="text" class="form-control member_username" name="member_username"
                                        id="member_username" placeholder="Username Anggota">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="member_password">Password Anggota</label>
                                    <input type="text" class="form-control member_password" name="member_password"
                                        id="member_password" placeholder="Password Anggota">
                                </div>
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
