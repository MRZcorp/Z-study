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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
        
            // Identitas Mata Kuliah
            $table->foreignId('dosen_id')
                  ->constrained('dosens')
                  ->onDelete('cascade');
            $table->foreignId('mata_kuliah_id')
                    ->constrained('mata_kuliahs')
                    ->cascadeOnDelete();
            
        
            // Kelas
            $table->string('nama_kelas'); // A, B, C
            $table->string('jadwal_kelas');
            $table->enum('hari_kelas', [
                'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
            ]);
        
            $table->time('jam_mulai');
            $table->time('jam_selesai');
        
           
            // Kuota
            $table->unsignedSmallInteger('kuota_maksimal');
            $table->unsignedSmallInteger('kuota_terdaftar')->default(0);
        
            $table->string('bg_image')->nullable();      // background header card
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
