<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi_downloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materi_id')->constrained('materi_kelas')->cascadeOnDelete();
            $table->foreignId('mahasiswa_id')->constrained('mahasiswas')->cascadeOnDelete();
            $table->timestamp('downloaded_at')->nullable();
            $table->timestamps();

            $table->unique(['materi_id', 'mahasiswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi_downloads');
    }
};
