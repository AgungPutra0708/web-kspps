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
        return $this->hasMany(TransaksiSimpananModel::class, 'id_anggota')
            ->where('id_simpanan', $idSimpanan);
    }
}
