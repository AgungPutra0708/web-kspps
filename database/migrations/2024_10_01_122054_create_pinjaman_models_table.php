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
        Schema::create('pinjamans', function (Blueprint $table) {
            $table->id();
            $table->integer('id_pembiayaan');
            $table->integer('id_anggota');
            $table->decimal('besar_pinjaman', 15, 2);
            $table->decimal('besar_margin', 15, 2);
            $table->integer('lama_pinjaman');
            $table->text('keterangan_pinjaman');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pinjamans');
    }
};
