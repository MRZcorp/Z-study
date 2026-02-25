<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('diskusis', function (Blueprint $table) {
            if (!Schema::hasColumn('diskusis', 'lampiran_path')) {
                $table->string('lampiran_path')->nullable()->after('pesan');
            }
            if (!Schema::hasColumn('diskusis', 'lampiran_name')) {
                $table->string('lampiran_name')->nullable()->after('lampiran_path');
            }
            if (!Schema::hasColumn('diskusis', 'lampiran_mime')) {
                $table->string('lampiran_mime')->nullable()->after('lampiran_name');
            }
            if (!Schema::hasColumn('diskusis', 'lampiran_size')) {
                $table->unsignedBigInteger('lampiran_size')->nullable()->after('lampiran_mime');
            }
        });
    }

    public function down(): void
    {
        Schema::table('diskusis', function (Blueprint $table) {
            foreach (['lampiran_size', 'lampiran_mime', 'lampiran_name', 'lampiran_path'] as $col) {
                if (Schema::hasColumn('diskusis', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

