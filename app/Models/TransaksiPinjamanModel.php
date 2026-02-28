<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiPinjamanModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "transaksi_pinjamans";

    protected $guarded = [];

    public function anggota()
    {
        return $this->belongsTo(AnggotaModel::class, 'id_anggota');
    }

    public function pembiayaan()
    {
        return $this->belongsTo(PembiayaanModel::class, 'id_pembiayaan');
    }

    public function pinjaman()
    {
        return $this->belongsTo(PinjamanModel::class, 'id_pinjaman');
    }

    public function petugas()
    {
        return $this->belongsTo(PetugasModel::class, 'id_petugas');
    }
}
