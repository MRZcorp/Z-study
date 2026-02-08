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
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
      ->constrained('users')
      ->cascadeOnDelete();
            $table->foreignId('fakultas_id')
      ->constrained('fakultas')
      ->cascadeOnDelete();
            
            $table->string('nidn')->unique();
            $table->string('email')->unique();
            $table->string('no_hp')->nullable();
            $table->string('gelar')->nullable();
            $table->string('poto_profil')->nullable();
            $table->string('bg')->nullable();
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosens');
    }
};
