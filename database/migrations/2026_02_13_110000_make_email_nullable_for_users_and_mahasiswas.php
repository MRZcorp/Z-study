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
        DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NULL');
        DB::statement('ALTER TABLE mahasiswas MODIFY email VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE users SET email = CONCAT('restored_user_', id, '@local.invalid') WHERE email IS NULL");
        DB::statement("UPDATE mahasiswas SET email = CONCAT('restored_mhs_', id, '@local.invalid') WHERE email IS NULL");
        DB::statement('ALTER TABLE users MODIFY email VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE mahasiswas MODIFY email VARCHAR(255) NOT NULL');
    }
};
