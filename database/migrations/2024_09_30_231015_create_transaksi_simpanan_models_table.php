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
        Schema::create('transaksi_simpanans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_simpanan');
            $table->integer('id_anggota');
            $table->enum('metode_transaksi', ['+', '-']);
            $table->decimal('jumlah_setoran', 15, 2);
            $table->text('keterangan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_simpanans');
    }
};
