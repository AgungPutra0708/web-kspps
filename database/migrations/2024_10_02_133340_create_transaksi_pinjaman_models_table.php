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
        Schema::create('transaksi_pinjamans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pinjaman');
            $table->integer('id_pembiayaan');
            $table->integer('id_anggota');
            $table->decimal('angsur_pinjaman', 15, 2);
            $table->decimal('angsur_margin', 15, 2);
            $table->integer('angsuran_ke');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pinjamans');
    }
};
