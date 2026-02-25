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
            $table->enum('jenjang', ['d3', 's1'])->nullable();
            $table->foreignId('fakultas_id')
      ->constrained('fakultas')
      ->cascadeOnDelete();
            $table->year('angkatan');
            $table->unsignedTinyInteger('semester_aktif')->default(1);
            $table->decimal('ips_terakhir', 5, 2)->default(0);
            $table->decimal('ipk', 5, 2)->default(0);
            $table->unsignedTinyInteger('maks_sks')->default(24);
            $table->string('status_akademik')->default('AKTIF');
            $table->unsignedTinyInteger('ips_below_2_count')->default(0);
            $table->unsignedTinyInteger('ipk_below_2_semester_count')->default(0);
            $table->unsignedTinyInteger('last_ips_semester')->nullable();
            $table->unsignedTinyInteger('last_ipk_semester')->nullable();
            $table->string('email')->unique();
            $table->string('poto_profil')->nullable();
            $table->string('bg')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
