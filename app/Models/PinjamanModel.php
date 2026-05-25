<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PinjamanModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "pinjamans";

    protected $guarded = [];

    public function pembiayaan()
    {
        return $this->belongsTo(PembiayaanModel::class, 'id_pembiayaan', 'id');
    }

    public function anggota()
    {
        return $this->belongsTo(AnggotaModel::class, 'id_anggota', 'id');
    }
}
