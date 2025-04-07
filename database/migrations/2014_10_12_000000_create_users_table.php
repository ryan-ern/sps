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
        Schema::create('users', function (Blueprint $table) {
            $table->string('nisn')->primary();
            $table->string('fullname');
            $table->string('username')->unique();
            $table->string('kelas');
            $table->string('password');
            $table->enum('role', ['admin', 'guru', 'siswa']);
            $table->enum('status', ['aktif', 'tidak aktif']);
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
