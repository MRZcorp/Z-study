<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengumpulan_tugas', function (Blueprint $table) {
            $table->foreignId('tugas_id')->after('id')->constrained('tugas')->onDelete('cascade');
            $table->foreignId('mahasiswa_id')->after('tugas_id')->constrained('mahasiswas')->onDelete('cascade');
            $table->string('file_path')->nullable()->after('mahasiswa_id');
            $table->string('file_name')->nullable()->after('file_path');
            $table->dateTime('submitted_at')->nullable()->after('file_name');
            $table->unsignedSmallInteger('nilai')->nullable()->after('submitted_at');
        });
    }

    public function down(): void
    {
        Schema::table('pengumpulan_tugas', function (Blueprint $table) {
            $table->dropColumn(['nilai', 'submitted_at', 'file_name', 'file_path']);
            $table->dropConstrainedForeignId('mahasiswa_id');
            $table->dropConstrainedForeignId('tugas_id');
        });
    }
};
