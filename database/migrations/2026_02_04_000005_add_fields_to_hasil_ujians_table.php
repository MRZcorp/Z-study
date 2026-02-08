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
            if (!Schema::hasColumn('hasil_ujians', 'mahasiswa_id')) {
                $table->unsignedBigInteger('mahasiswa_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('hasil_ujians', 'ujian_id')) {
                $table->unsignedBigInteger('ujian_id')->nullable()->after('mahasiswa_id');
            }
            if (!Schema::hasColumn('hasil_ujians', 'submitted_at')) {
                $table->dateTime('submitted_at')->nullable()->after('ujian_id');
            }
            if (!Schema::hasColumn('hasil_ujians', 'nilai')) {
                $table->unsignedSmallInteger('nilai')->nullable()->after('submitted_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_ujians', function (Blueprint $table) {
            $columns = ['mahasiswa_id', 'ujian_id', 'submitted_at', 'nilai'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('hasil_ujians', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
