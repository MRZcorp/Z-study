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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
      ->constrained('users')
      ->cascadeOnDelete();
            $table->string('nim')->unique();
            $table->foreignId('nama_prodi_id')
      ->constrained('program_studis')
      ->cascadeOnDelete();
            $table->foreignId('fakultas_id')
      ->constrained('fakultas')
      ->cascadeOnDelete();
            $table->year('angkatan');
            $table->string('email')->unique();
            $table->string('poto_profil')->nullable();
            $table->string('bg')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
