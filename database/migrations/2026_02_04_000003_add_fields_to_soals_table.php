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
        Schema::table('soals', function (Blueprint $table) {
            if (!Schema::hasColumn('soals', 'ujian_id')) {
                $table->unsignedBigInteger('ujian_id')->nullable()->after('id');
            }
            if (!Schema::hasColumn('soals', 'tipe')) {
                $table->string('tipe')->default('essay')->after('ujian_id');
            }
            if (!Schema::hasColumn('soals', 'pertanyaan')) {
                $table->text('pertanyaan')->nullable()->after('tipe');
            }
            if (!Schema::hasColumn('soals', 'media_path')) {
                $table->string('media_path')->nullable()->after('pertanyaan');
            }
            if (!Schema::hasColumn('soals', 'bobot')) {
                $table->decimal('bobot', 6, 2)->nullable()->after('media_path');
            }
            if (!Schema::hasColumn('soals', 'options')) {
                $table->json('options')->nullable()->after('bobot');
            }
            if (!Schema::hasColumn('soals', 'pg_correct')) {
                $table->string('pg_correct')->nullable()->after('options');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('soals', function (Blueprint $table) {
            if (Schema::hasColumn('soals', 'options')) {
                $table->dropColumn('options');
            }
            if (Schema::hasColumn('soals', 'bobot')) {
                $table->dropColumn('bobot');
            }
            if (Schema::hasColumn('soals', 'pg_correct')) {
                $table->dropColumn('pg_correct');
            }
            if (Schema::hasColumn('soals', 'media_path')) {
                $table->dropColumn('media_path');
            }
            if (Schema::hasColumn('soals', 'pertanyaan')) {
                $table->dropColumn('pertanyaan');
            }
            if (Schema::hasColumn('soals', 'tipe')) {
                $table->dropColumn('tipe');
            }
            if (Schema::hasColumn('soals', 'ujian_id')) {
                $table->dropColumn('ujian_id');
            }
        });
    }
};
