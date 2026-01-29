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
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            // RELASI KE KELAS
            $table->foreignId('nama_kelas_id')
                  ->constrained('kelas')
                  ->onDelete('cascade');

            $table->foreignId('mata_kuliah_id')
                  ->constrained('mata_kuliahs')
                  ->onDelete('cascade');
        
            // DATA TUGAS
            $table->string('nama_tugas');
            $table->text('detail_tugas')->nullable();
            $table->string('file_tugas')->nullable(); // path file
            $table->dateTime('deadline');
        
         
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
