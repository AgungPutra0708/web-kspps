<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiAnggotaModel extends Model
{
    use HasFactory;

    protected $table = 'transaksi_anggotas';

    protected $fillable = [
        'id_anggota',
        'id_transaksi_simpanan_asal',
        'id_rekening_simpanan_asal',
        'no_rekening_simpanan_asal',
        'id_transaksi_simpanan_tujuan',
        'id_rekening_simpanan_tujuan',
        'no_rekening_simpanan_tujuan',
        'id_transaksi_pinjaman',
        'id_pinjaman_tujuan',
        'no_pinjaman_tujuan',
        'jumlah',
        'tanggal_transaksi',
        'jenis_transaksi',
        'keterangan',
    ];

    public function anggota()
    {
        return $this->belongsTo(AnggotaModel::class, 'id_anggota');
    }

    public function transaksiSimpananAsal()
    {
        return $this->belongsTo(TransaksiSimpananModel::class, 'id_transaksi_simpanan_asal');
    }

    public function transaksiSimpananTujuan()
    {
        return $this->belongsTo(TransaksiSimpananModel::class, 'id_transaksi_simpanan_tujuan');
    }

    public function transaksiPinjaman()
    {
        return $this->belongsTo(TransaksiPinjamanModel::class, 'id_transaksi_pinjaman');
    }

    public function rekeningSimpananAsal()
    {
        return $this->belongsTo(RekeningSimpananModel::class, 'id_rekening_simpanan_asal');
    }

    public function rekeningSimpananTujuan()
    {
        return $this->belongsTo(RekeningSimpananModel::class, 'id_rekening_simpanan_tujuan');
    }

    public function pinjamanAsal()
    {
        return $this->belongsTo(PinjamanModel::class, 'id_pinjaman_asal');
    }

    public function pinjamanTujuan()
    {
        return $this->belongsTo(PinjamanModel::class, 'id_pinjaman_tujuan');
    }
}
