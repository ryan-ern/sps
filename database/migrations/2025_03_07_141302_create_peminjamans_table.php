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
        Schema::create('peminjamans', function (Blueprint $table) {
            $table->id()->primary();
            $table->unsignedBigInteger('nisn');
            $table->foreign('nisn')->references('nisn')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('no_regis');
            $table->foreign('no_regis')->references('no_regis')->on('bukus')->onDelete('cascade')->onUpdate('cascade');
            $table->string('fullname');
            $table->text('judul');
            $table->string('tgl_pinjam');
            $table->string('tgl_kembali');
            $table->string('denda');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};
