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
        Schema::table('hasil_ujians', function (Blueprint $table) {
            if (!Schema::hasColumn('hasil_ujians', 'nilai_kecepatan')) {
                $table->decimal('nilai_kecepatan', 8, 2)->default(0)->after('nilai');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_ujians', function (Blueprint $table) {
            if (Schema::hasColumn('hasil_ujians', 'nilai_kecepatan')) {
                $table->dropColumn('nilai_kecepatan');
            }
        });
    }
};
