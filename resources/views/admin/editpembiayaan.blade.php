@extends('layout.main')

@section('content')
    <!-- Main Content -->
    <div id="content" style="height: 100vh">
        <!-- Begin Page Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <form action="{{ route('pinjaman.update', Crypt::encrypt($pinjaman->id)) }}" method="post"
                        id="pembiayaanForm">
                        @csrf
                        @method('PUT') <!-- Gunakan metode PUT untuk update -->

                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold" style="color: #08786B">Edit Pembiayaan Anggota</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="loan_product">Pilih Produk Pembiayaan</label>
                                            <select class="form-control select2 loan_product" style="width: 100%;"
                                                name="loan_product" id="loan_product">
                                                <option></option>
                                                @foreach ($dataPembiayaan as $data)
                                                    <option value="{{ $data->id }}"
                                                        {{ $data->id == $pinjaman->id_pembiayaan ? 'selected' : '' }}>
                                                        ({{ $data->no_pembiayaan }})
                                                        {{ $data->nama_pembiayaan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="member_name">Pilih Nama Anggota*</label>
                                            <select class="form-control select2 member_name" style="width: 100%;"
                                                name="member_name" id="member_name">
                                                <option></option>
                                                @foreach ($dataAnggota as $data)
                                                    <option value="{{ $data->id }}"
                                                        {{ $data->id == $pinjaman->id_anggota ? 'selected' : '' }}>
                                                        ({{ $data->no_anggota }})
                                                        {{ $data->nama_anggota }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="nominal_pinjaman">Nominal Pinjaman</label>
                                            <input type="text" class="form-control nominal_pinjaman"
                                                name="nominal_pinjaman" id="nominal_pinjaman" placeholder="Nominal Pinjaman"
                                                value="{{ $pinjaman->besar_pinjaman }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="nominal_margin">Nominal Margin</label>
                                            <input type="text" class="form-control nominal_margin" name="nominal_margin"
                                                id="nominal_margin" placeholder="Nominal Margin"
                                                value="{{ $pinjaman->besar_margin }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="nominal_angsuran_pinjaman">Nominal Angsuran Pinjaman</label>
                                            <input type="text" class="form-control nominal_angsuran_pinjaman"
                                                name="nominal_angsuran_pinjaman" id="nominal_angsuran_pinjaman"
                                                placeholder="Nominal Angsuran Pinjaman"
                                                value="{{ $pinjaman->angsur_pinjaman }}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="nominal_angsuran_margin">Nominal Angsuran Margin</label>
                                            <input type="text" class="form-control nominal_angsuran_margin"
                                                name="nominal_angsuran_margin" id="nominal_angsuran_margin"
                                                placeholder="Nominal Angsuran Margin"
                                                value="{{ $pinjaman->angsur_margin }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="lama_pinjaman">Lama Pinjaman</label>
                                            <div class="row">
                                                <div class="col-auto pr-0">
                                                    <select class="kondisi_pinjaman form-control" name="kondisi_pinjaman"
                                                        id="kondisi_pinjaman">
                                                        <option value="hari"
                                                            {{ $pinjaman->kondisi_pinjaman == 'hari' ? 'selected' : '' }}>
                                                            Hari</option>
                                                        <option value="pekan"
                                                            {{ $pinjaman->kondisi_pinjaman == 'pekan' ? 'selected' : '' }}>
                                                            Pekan</option>
                                                        <option value="bulan"
                                                            {{ $pinjaman->kondisi_pinjaman == 'bulan' ? 'selected' : '' }}>
                                                            Bulan</option>
                                                    </select>
                                                </div>
                                                <div class="col pl-1">
                                                    <input type="text" class="form-control lama_pinjaman"
                                                        name="lama_pinjaman" id="lama_pinjaman" placeholder="Lama Pinjaman"
                                                        value="{{ $pinjaman->lama_pinjaman }}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="status_pinjaman">Status Pinjaman</label>
                                            <select class="status_pinjaman form-control" name="status_pinjaman"
                                                id="status_pinjaman">
                                                <option value="on_going"
                                                    {{ $pinjaman->status_pinjaman == 'on_going' ? 'selected' : '' }}>
                                                    Berjalan</option>
                                                <option value="done"
                                                    {{ $pinjaman->status_pinjaman == 'done' ? 'selected' : '' }}>
                                                    Lunas</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="loan_desc">Keterangan</label>
                                            <textarea class="form-control loan_desc" name="loan_desc" id="loan_desc">{{ $pinjaman->keterangan_pinjaman }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary float-right ml-1">Simpan</button>

                    </form>
                    <form action="{{ route('delete_pembiayaan', Crypt::encrypt($pinjaman->id)) }}" method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger float-right"
                            onclick="return confirm('Anda yakin akan dihapus?');">Hapus</button>
                    </form>
                    <a href="{{ route('dashboard') }}" class="btn btn-danger float-left">Batal</a>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <!-- /.container-fluid -->
@endsection
