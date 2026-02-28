<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransaksiSimpananModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "transaksi_simpanans";

    protected $guarded = [];

    // Relasi ke SimpananModel
    public function simpanan()
    {
        return $this->belongsTo(SimpananModel::class, 'id_simpanan');
    }

    public function rekeningSimpanan()
    {
        return $this->belongsTo(RekeningSimpananModel::class, 'id_rekening_simpanan');
    }

    public function petugas()
    {
        return $this->belongsTo(PetugasModel::class, 'id_petugas');
    }
}
