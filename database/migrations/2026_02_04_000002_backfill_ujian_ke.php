<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('ujians', 'ujian_ke')) {
            return;
        }
        $rows = DB::table('ujians')
            ->orderBy('nama_kelas_id')
            ->orderBy('created_at')
            ->get(['id', 'nama_kelas_id']);

        $counters = [];
        foreach ($rows as $row) {
            $kelasId = $row->nama_kelas_id ?? 0;
            if (!isset($counters[$kelasId])) {
                $counters[$kelasId] = 0;
            }
            $counters[$kelasId]++;
            DB::table('ujians')->where('id', $row->id)->update([
                'ujian_ke' => $counters[$kelasId],
            ]);
        }
    }

    public function down(): void
    {
        // no-op
    }
};
