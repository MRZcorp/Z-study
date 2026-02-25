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
            $table->foreignId('beasiswa_id')->nullable()->after('jenjang')->constrained('beasiswas')->nullOnDelete();
        });

        if (Schema::hasColumn('mahasiswas', 'beasiswa')) {
            $legacyNames = DB::table('mahasiswas')
                ->whereNotNull('beasiswa')
                ->where('beasiswa', '!=', '')
                ->distinct()
                ->pluck('beasiswa');

            foreach ($legacyNames as $nama) {
                DB::table('beasiswas')->updateOrInsert(
                    ['nama' => $nama],
                    [
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }

            $rows = DB::table('mahasiswas')
                ->select('id', 'beasiswa')
                ->whereNotNull('beasiswa')
                ->where('beasiswa', '!=', '')
                ->get();

            foreach ($rows as $row) {
                $beasiswaId = DB::table('beasiswas')
                    ->where('nama', $row->beasiswa)
                    ->value('id');
                if ($beasiswaId) {
                    DB::table('mahasiswas')
                        ->where('id', $row->id)
                        ->update(['beasiswa_id' => $beasiswaId]);
                }
            }

            Schema::table('mahasiswas', function (Blueprint $table) {
                $table->dropColumn('beasiswa');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('beasiswa')->nullable()->after('jenjang');
        });

        $rows = DB::table('mahasiswas')
            ->select('mahasiswas.id', 'beasiswas.nama')
            ->leftJoin('beasiswas', 'beasiswas.id', '=', 'mahasiswas.beasiswa_id')
            ->get();

        foreach ($rows as $row) {
            DB::table('mahasiswas')
                ->where('id', $row->id)
                ->update(['beasiswa' => $row->nama]);
        }

        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropForeign(['beasiswa_id']);
            $table->dropColumn('beasiswa_id');
        });
    }
};

