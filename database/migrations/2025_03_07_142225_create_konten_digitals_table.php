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
        Schema::create('konten_digitals', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('nuptk');
            $table->foreign('nuptk')->references('nisn')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->enum('jenis', ['buku digital', 'video']);
            $table->text('judul');
            $table->text('url')->nullable();
            $table->string('cover')->nullable();
            $table->string('file_path')->nullable();
            $table->string('pengarang');
            $table->string('penerbit');
            $table->integer('dilihat')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konten_digitals');
    }
};
