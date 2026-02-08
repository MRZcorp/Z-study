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
        Schema::table('kelas_mahasiswa', function (Blueprint $table) {
            $table->enum('status', ['menunggu', 'disetujui'])
                ->default('menunggu')
                ->after('mahasiswa_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas_mahasiswa', function (Blueprint $table) {
            if (Schema::hasColumn('kelas_mahasiswa', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
