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
        Schema::table('inaproc_accounts', function (Blueprint $table) {
            // Menambahkan kolom jenis_data setelah kolom 'sumber'
            $table->enum('jenis_data', ['Katalog v.6', 'SPSE'])->after('sumber')->default('Katalog v.6');
        });
    }

    public function down(): void
    {
        Schema::table('inaproc_accounts', function (Blueprint $table) {
            $table->dropColumn('jenis_data');
        });
    }
};
