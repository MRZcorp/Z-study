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
            if (!Schema::hasColumn('diskusis', 'ujian_id')) {
                $table->foreignId('ujian_id')
                    ->nullable()
                    ->after('kelas_id')
                    ->constrained('ujians')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('diskusis', 'tugas_id')) {
                $table->foreignId('tugas_id')
                    ->nullable()
                    ->after('ujian_id')
                    ->constrained('tugas')
                    ->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diskusis', function (Blueprint $table) {
            if (Schema::hasColumn('diskusis', 'tugas_id')) {
                $table->dropForeign(['tugas_id']);
                $table->dropColumn('tugas_id');
            }

            if (Schema::hasColumn('diskusis', 'ujian_id')) {
                $table->dropForeign(['ujian_id']);
                $table->dropColumn('ujian_id');
            }
        });
    }
};
