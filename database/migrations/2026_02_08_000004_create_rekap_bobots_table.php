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
        Schema::create('rekap_bobots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kelas_id')->unique();
            $table->decimal('harian', 6, 2)->default(15);
            $table->decimal('keaktifan', 6, 2)->default(6.25);
            $table->decimal('kecepatan', 6, 2)->default(3.75);
            $table->decimal('absensi', 6, 2)->default(5);
            $table->decimal('uts', 6, 2)->default(30);
            $table->decimal('uas', 6, 2)->default(40);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_bobots');
    }
};
