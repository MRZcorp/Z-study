<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE kelas_mahasiswa MODIFY status ENUM('menunggu','disetujui','ditolak') NOT NULL DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE kelas_mahasiswa MODIFY status ENUM('menunggu','disetujui') NOT NULL DEFAULT 'menunggu'");
    }
};
