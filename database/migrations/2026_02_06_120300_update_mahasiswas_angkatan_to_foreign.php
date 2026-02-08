<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->foreignId('angkatan_id')->nullable()->after('fakultas_id');
        });

        if (Schema::hasColumn('mahasiswas', 'angkatan')) {
            $angkatanMap = DB::table('angkatans')
                ->get(['id', 'tahun'])
                ->keyBy('tahun');

            DB::table('mahasiswas')->select('id', 'angkatan')->orderBy('id')->chunk(200, function ($rows) use ($angkatanMap) {
                foreach ($rows as $row) {
                    $angkatanId = $angkatanMap[$row->angkatan]->id ?? null;
                    if ($angkatanId) {
                        DB::table('mahasiswas')
                            ->where('id', $row->id)
                            ->update(['angkatan_id' => $angkatanId]);
                    }
                }
            });
        }

        Schema::table('mahasiswas', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswas', 'angkatan')) {
                $table->dropColumn('angkatan');
            }
            $table->foreign('angkatan_id')->references('id')->on('angkatans')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswas', 'angkatan_id')) {
                $table->dropForeign(['angkatan_id']);
                $table->dropColumn('angkatan_id');
            }
            $table->year('angkatan')->nullable();
        });
    }
};
