<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->unsignedInteger('tugas_ke')->nullable()->after('nama_kelas_id');
            $table->unique(['nama_kelas_id', 'tugas_ke']);
        });

        $tugas = DB::table('tugas')
            ->orderBy('nama_kelas_id')
            ->orderBy('created_at')
            ->get();

        $counter = [];
        foreach ($tugas as $row) {
            $kelasId = $row->nama_kelas_id;
            $counter[$kelasId] = ($counter[$kelasId] ?? 0) + 1;
            DB::table('tugas')->where('id', $row->id)->update(['tugas_ke' => $counter[$kelasId]]);
        }
    }

    public function down(): void
    {
        Schema::table('tugas', function (Blueprint $table) {
            $table->dropUnique(['nama_kelas_id', 'tugas_ke']);
            $table->dropColumn('tugas_ke');
        });
    }
};
