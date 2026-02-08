<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('nama_kelas');
        });

        $rows = DB::table('kelas')
            ->leftJoin('mata_kuliahs', 'mata_kuliahs.id', '=', 'kelas.mata_kuliah_id')
            ->select('kelas.id', 'kelas.nama_kelas', 'mata_kuliahs.mata_kuliah')
            ->get();

        foreach ($rows as $row) {
            $base = trim(($row->mata_kuliah ?? '') . ' ' . ($row->nama_kelas ?? ''));
            $slug = Str::slug($base, '_');
            if ($slug === '') {
                $slug = 'kelas_' . $row->id;
            }

            $exists = DB::table('kelas')
                ->where('slug', $slug)
                ->where('id', '!=', $row->id)
                ->exists();

            if ($exists) {
                $slug = $slug . '_' . $row->id;
            }

            DB::table('kelas')->where('id', $row->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
