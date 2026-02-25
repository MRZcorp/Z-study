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
        Schema::table('diskusis', function (Blueprint $table) {
            if (!Schema::hasColumn('diskusis', 'kelas_id')) {
                $table->foreignId('kelas_id')->nullable()->after('id')->constrained('kelas')->nullOnDelete();
            }

            if (!Schema::hasColumn('diskusis', 'user_id')) {
                $table->foreignId('user_id')->nullable()->after('kelas_id')->constrained('users')->nullOnDelete();
            }

            if (!Schema::hasColumn('diskusis', 'pesan')) {
                $table->text('pesan')->nullable()->after('user_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diskusis', function (Blueprint $table) {
            if (Schema::hasColumn('diskusis', 'pesan')) {
                $table->dropColumn('pesan');
            }

            if (Schema::hasColumn('diskusis', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }

            if (Schema::hasColumn('diskusis', 'kelas_id')) {
                $table->dropForeign(['kelas_id']);
                $table->dropColumn('kelas_id');
            }
        });
    }
};
