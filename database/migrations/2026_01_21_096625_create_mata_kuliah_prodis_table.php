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
        Schema::create('mata_kuliah_prodis', function (Blueprint $table) {
    $table->id();
    $table->foreignId('mata_kuliah_id')
        ->constrained('mata_kuliahs')
        ->cascadeOnDelete();

    $table->foreignId('nama_prodi_id')
        ->constrained('program_studis')
        ->cascadeOnDelete();

    $table->unique(['mata_kuliah_id', 'nama_prodi_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_kuliah_prodis');
    }
};
