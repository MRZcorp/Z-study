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
        Schema::create('rekap_nilais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kelas_id')->nullable();
            $table->unsignedBigInteger('mahasiswa_id')->nullable();
            $table->decimal('rata_tugas', 8, 2)->default(0);
            $table->decimal('rata_kecepatan_tugas', 8, 2)->default(0);
            $table->decimal('rata_ujian', 8, 2)->default(0);
            $table->decimal('rata_kecepatan_ujian', 8, 2)->default(0);
            $table->string('keaktifan', 10)->nullable();
            $table->integer('absensi')->nullable();
            $table->timestamps();

            $table->unique(['kelas_id', 'mahasiswa_id'], 'rekap_nilais_kelas_mahasiswa_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_nilais');
    }
};
