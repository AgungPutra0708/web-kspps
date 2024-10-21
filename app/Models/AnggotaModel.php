<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AnggotaModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "anggotas";

    protected $guarded = [];

    public function rembug()
    {
        return $this->belongsTo(RembugModel::class, 'id_rembug', 'id');
    }

    // Relasi ke model TransaksiSimpanan dengan kondisi id_anggota dan id_simpanan
    public function transaksiSimpanans($idSimpanan)
    {
        $query = $this->hasMany(TransaksiSimpananModel::class, 'id_anggota');

        if ($idSimpanan) {
            $query->where('id_simpanan', $idSimpanan);
        }

        return $query;
    }

    // Relasi ke model TransaksiSimpanan dengan kondisi id_anggota dan id_simpanan
    public function transaksiAllSimpanans()
    {
        return $this->hasMany(TransaksiSimpananModel::class, 'id_anggota')
            ->whereHas('simpanan', function ($query) {
                $query->withoutTrashed(); // Hanya ambil simpanan yang tidak di-soft deleted
            });
    }


    // Relasi ke model Pinjaman dengan kondisi id_anggota dan id_pembiayaan
    public function pembiayaanAnggota($idPembiayaan)
    {
        return $this->hasMany(PinjamanModel::class, 'id_anggota')
            ->where('id_pembiayaan', $idPembiayaan)
            ->where('status_pinjaman', 'on_going');
    }
}
