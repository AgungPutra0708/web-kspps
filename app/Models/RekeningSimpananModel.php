<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RekeningSimpananModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "rekening_simpanans";

    protected $guarded = [];

    public function simpanan()
    {
        return $this->belongsTo(SimpananModel::class, 'id_simpanan', 'id');
    }

    public function anggota()
    {
        return $this->belongsTo(AnggotaModel::class, 'id_anggota', 'id');
    }
}
