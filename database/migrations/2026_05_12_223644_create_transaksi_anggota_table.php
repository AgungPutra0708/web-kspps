<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksi_anggotas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_anggota');
            $table->integer('id_transaksi_simpanan_asal')->nullable();
            $table->integer('id_rekening_simpanan_asal')->nullable();
            $table->string('no_rekening_simpanan_asal')->nullable();
            $table->integer('id_transaksi_simpanan_tujuan')->nullable();
            $table->integer('id_rekening_simpanan_tujuan')->nullable();
            $table->string('no_rekening_simpanan_tujuan')->nullable();
            $table->integer('id_transaksi_pinjaman')->nullable();
            $table->integer('id_pinjaman_tujuan')->nullable();
            $table->string('no_pinjaman_tujuan')->nullable();
            $table->decimal('jumlah', 15, 2);
            $table->dateTime('tanggal_transaksi');
            $table->enum('jenis_transaksi', ['simpanan', 'pinjaman', 'transfer']);
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_anggotas');
    }
};
