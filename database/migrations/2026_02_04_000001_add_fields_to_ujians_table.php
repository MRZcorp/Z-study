<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ujians', function (Blueprint $table) {
            $table->foreignId('nama_kelas_id')->nullable()->after('id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('mata_kuliah_id')->nullable()->after('nama_kelas_id')->constrained('mata_kuliahs')->onDelete('cascade');
            $table->string('nama_ujian')->nullable()->after('mata_kuliah_id');
            $table->unsignedSmallInteger('ujian_ke')->nullable()->after('nama_ujian');
            $table->text('deskripsi')->nullable()->after('ujian_ke');
            $table->dateTime('mulai_ujian')->nullable()->after('deskripsi');
            $table->dateTime('deadline')->nullable()->after('mulai_ujian');
            $table->string('file_path')->nullable()->after('deadline');
            $table->string('file_name')->nullable()->after('file_path');
        });
    }

    public function down(): void
    {
        Schema::table('ujians', function (Blueprint $table) {
            $table->dropColumn(['file_name', 'file_path', 'deadline', 'mulai_ujian', 'deskripsi', 'nama_ujian']);
            $table->dropConstrainedForeignId('mata_kuliah_id');
            $table->dropConstrainedForeignId('nama_kelas_id');
        });
    }
};
