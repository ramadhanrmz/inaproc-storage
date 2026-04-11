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
            // Menghapus index unik dari kolom user_id
            $table->dropUnique(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::table('inaproc_accounts', function (Blueprint $table) {
            // Mengembalikan menjadi unik jika di-rollback
            $table->unique('user_id');
        });
    }
};
