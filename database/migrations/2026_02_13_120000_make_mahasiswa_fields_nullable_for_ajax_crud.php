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
        DB::statement('ALTER TABLE users MODIFY name VARCHAR(255) NULL');
        DB::statement('ALTER TABLE mahasiswas MODIFY nim VARCHAR(255) NULL');
        DB::statement('ALTER TABLE mahasiswas MODIFY nama_prodi_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE mahasiswas MODIFY fakultas_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE mahasiswas MODIFY status_akademik VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE users SET name = CONCAT('User ', id) WHERE name IS NULL");
        DB::statement("UPDATE mahasiswas SET nim = CONCAT('NIM-', id) WHERE nim IS NULL");
        DB::statement('ALTER TABLE users MODIFY name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE mahasiswas MODIFY nim VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE mahasiswas MODIFY nama_prodi_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE mahasiswas MODIFY fakultas_id BIGINT UNSIGNED NOT NULL');
        DB::statement("UPDATE mahasiswas SET status_akademik = 'AKTIF' WHERE status_akademik IS NULL");
        DB::statement("ALTER TABLE mahasiswas MODIFY status_akademik VARCHAR(255) NOT NULL DEFAULT 'AKTIF'");
    }
};
