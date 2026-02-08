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
        Schema::table('rekap_nilais', function (Blueprint $table) {
            $table->unsignedBigInteger('kelas_id')->nullable()->after('id');
            $table->unsignedBigInteger('mahasiswa_id')->nullable()->after('kelas_id');
            $table->decimal('rata_tugas', 8, 2)->default(0)->after('mahasiswa_id');
            $table->decimal('rata_kecepatan_tugas', 8, 2)->default(0)->after('rata_tugas');
            $table->decimal('rata_ujian', 8, 2)->default(0)->after('rata_kecepatan_tugas');
            $table->decimal('rata_kecepatan_ujian', 8, 2)->default(0)->after('rata_ujian');
            $table->string('keaktifan', 10)->nullable()->after('rata_kecepatan_ujian');
            $table->integer('absensi')->nullable()->after('keaktifan');

            $table->unique(['kelas_id', 'mahasiswa_id'], 'rekap_nilais_kelas_mahasiswa_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rekap_nilais', function (Blueprint $table) {
            $table->dropUnique('rekap_nilais_kelas_mahasiswa_unique');
            $table->dropColumn([
                'kelas_id',
                'mahasiswa_id',
                'rata_tugas',
                'rata_kecepatan_tugas',
                'rata_ujian',
                'rata_kecepatan_ujian',
                'keaktifan',
                'absensi',
            ]);
        });
    }
};
