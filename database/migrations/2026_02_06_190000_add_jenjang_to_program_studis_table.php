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
        Schema::table('program_studis', function (Blueprint $table) {
            $table->unsignedSmallInteger('s1')->nullable()->after('fakultas_id');
            $table->unsignedSmallInteger('d3')->nullable()->after('s1');
        });

        DB::table('program_studis')->orderBy('id')->chunk(200, function ($rows) {
            foreach ($rows as $row) {
                DB::table('program_studis')
                    ->where('id', $row->id)
                    ->update([
                        's1' => $row->s1 ?? random_int(144, 160),
                        'd3' => $row->d3 ?? random_int(108, 120),
                    ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            if (Schema::hasColumn('program_studis', 's1')) {
                $table->dropColumn('s1');
            }
            if (Schema::hasColumn('program_studis', 'd3')) {
                $table->dropColumn('d3');
            }
        });
    }
};
