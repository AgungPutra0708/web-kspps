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
        Schema::create('profile_koperasis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_koperasi');
            $table->string('nama_koperasi_lengkap')->nullable();
            $table->text('logo_koperasi_indonesia')->nullable();
            $table->text('logo_koperasi')->nullable();
            $table->text('alamat_koperasi')->nullable();
            $table->text('id_spreadsheet_shu')->nullable();
            $table->text('id_api_spreadsheet_shu')->nullable();
            $table->text('link_market')->nullable();
            $table->text('link_baitul_mal')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_koperasis');
    }
};
