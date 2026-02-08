<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->string('tahun_ajar')->nullable()->after('mata_kuliah_id');
            $table->enum('semester', ['ganjil', 'genap'])->nullable()->after('tahun_ajar');
        });

        DB::table('kelas')
            ->where('status', 'nonaktif')
            ->update(['status' => 'draft']);

        DB::statement("ALTER TABLE kelas MODIFY status ENUM('draft','aktif','selesai') NOT NULL DEFAULT 'draft'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE kelas MODIFY status ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif'");

        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn(['tahun_ajar', 'semester']);
        });
    }
};
