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
        Schema::create('soals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ujian_id')->nullable();
            $table->string('tipe')->default('essay');
            $table->text('pertanyaan')->nullable();
            $table->string('media_path')->nullable();
            $table->decimal('bobot', 6, 2)->nullable();
            $table->json('options')->nullable();
            $table->string('pg_correct')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soals');
    }
};
