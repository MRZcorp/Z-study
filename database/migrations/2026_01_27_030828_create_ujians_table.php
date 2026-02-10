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
        Schema::create('ujians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nama_kelas_id')->nullable()->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mata_kuliah_id')->nullable()->constrained('mata_kuliahs')->onDelete('cascade');
            $table->string('nama_ujian')->nullable();
            $table->unsignedSmallInteger('ujian_ke')->nullable();
            $table->text('deskripsi')->nullable();
            $table->dateTime('mulai_ujian')->nullable();
            $table->dateTime('deadline')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujians');
    }
};
