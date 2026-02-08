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
        Schema::create('dosen_walis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dosen_id')
                ->constrained('dosens')
                ->cascadeOnDelete();
            $table->foreignId('nama_prodi_id')
                ->constrained('program_studis')
                ->cascadeOnDelete();
            $table->foreignId('angkatan_id')
                ->constrained('angkatans')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen_walis');
    }
};
