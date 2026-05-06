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
        Schema::table('inaproc_accounts', function (Blueprint $table) {
            $table->foreignId('opd_id')->nullable()->after('nama')->constrained('opds')->onDelete('set null');
        });

        // Migrate data
        $uniqueOpds = DB::table('inaproc_accounts')->distinct()->pluck('opd');
        foreach ($uniqueOpds as $opdName) {
            if (empty($opdName)) continue;
            
            $opdId = DB::table('opds')->insertGetId([
                'nama' => $opdName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('inaproc_accounts')->where('opd', $opdName)->update(['opd_id' => $opdId]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inaproc_accounts', function (Blueprint $table) {
            $table->dropForeign(['opd_id']);
            $table->dropColumn('opd_id');
        });
    }
};
