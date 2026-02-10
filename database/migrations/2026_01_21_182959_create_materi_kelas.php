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
        Schema::create('materi_kelas', function (Blueprint $table) {
            $table->id();
            $table->string('judul_materi');
    $table->string('matkul');
    $table->unsignedTinyInteger('pertemuan')->nullable();
    $table->unsignedBigInteger('kelas_id')->nullable();
    $table->text('deskripsi');
    $table->string('file_path'); // path file
    $table->string('file_type'); // pdf, zip, mp4, dll
    $table->unsignedBigInteger('file_size')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_kelas');
    }
};
