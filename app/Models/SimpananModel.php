<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SimpananModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "simpanans";

    protected $guarded = [];

    public function transaksiSimpanans($idAnggota)
    {
        return $this->hasMany(TransaksiSimpananModel::class, 'id_simpanan')
            ->where('id_anggota', $idAnggota);
    }

    public function transaksiSimpanansAnggota($idAnggota, $idSimpanan)
    {
        return $this->hasMany(TransaksiSimpananModel::class, 'id_simpanan', $idSimpanan)
            ->where('id_anggota', $idAnggota);
    }
}
