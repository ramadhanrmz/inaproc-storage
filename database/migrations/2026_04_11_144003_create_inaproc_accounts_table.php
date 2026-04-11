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
        Schema::create('inaproc_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('opd'); // Perangkat Daerah
            $table->enum('status', ['PPK', 'PP', 'Bendahara', 'POKJA', 'Auditor', 'PA', 'KPA']); // Status pengguna
            $table->string('no_surat_permohonan');
            $table->string('perihal_permohonan');
            $table->string('no_sk');
            $table->string('user_id')->unique();
            $table->string('nik', 16);
            $table->string('nip', 18);
            $table->string('pangkat_gol');
            $table->string('jabatan');
            $table->string('no_hp');
            $table->text('alamat');
            $table->enum('sumber', ['Fisik', 'Digital']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inaproc_accounts');
    }
};
