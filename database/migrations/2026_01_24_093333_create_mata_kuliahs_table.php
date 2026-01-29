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
        Schema::create('mata_kuliahs', function (Blueprint $table) {
            $table->id();

            $table->string('kode_mata_kuliah')->unique();
            $table->string('mata_kuliah');
            $table->foreignId('nama_prodi_id')
                ->constrained('program_studis')
                ->cascadeOnDelete();
                

            $table->string('semester'); // Genap 2025/2026
            $table->tinyInteger('sks');
            
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliahs');
    }
};
// $table->foreignId('dosen_id')
            //       ->constrained('dosens')
            //       ->onDelete('cascade');
            // $table->text('deskripsi')->nullable();
