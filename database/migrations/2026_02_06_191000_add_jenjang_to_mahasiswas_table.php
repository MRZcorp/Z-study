<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->enum('jenjang', ['d3', 's1'])->nullable()->after('nama_prodi_id');
        });

        DB::table('mahasiswas')->orderBy('id')->chunk(200, function ($rows) {
            foreach ($rows as $row) {
                DB::table('mahasiswas')
                    ->where('id', $row->id)
                    ->update([
                        'jenjang' => $row->jenjang ?? (random_int(0, 1) === 0 ? 'd3' : 's1'),
                    ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            if (Schema::hasColumn('mahasiswas', 'jenjang')) {
                $table->dropColumn('jenjang');
            }
        });
    }
};
