<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('krs_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('mulai_tahun_ajar');
            $table->unsignedInteger('akhir_tahun_ajar');
            $table->enum('semester', ['ganjil', 'genap']);
            $table->enum('status', ['aktif', 'nonaktif'])->default('nonaktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('krs_settings');
    }
};
