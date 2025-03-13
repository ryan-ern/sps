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
        Schema::create('bukus', function (Blueprint $table) {
            $table->id('no_regis')->primary();
            $table->text('judul');
            $table->string('pengarang');
            $table->string('penerbit');
            $table->string('tahun');
            $table->string('stok');
            $table->text('keterangan')->default('-');
            $table->string('file_buku')->default('-');
            $table->string('file_cover')->default('-');
            $table->enum('jenis', ['referensi', 'paket'])->default('referensi');
            $table->enum('status', ['tersedia', 'tidak tersedia'])->default('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
