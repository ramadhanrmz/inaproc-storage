<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InaprocAccount extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nama', 'opd', 'status', 'no_surat_permohonan', 'perihal_permohonan',
        'no_sk', 'user_id', 'nik', 'nip', 'pangkat_gol', 'jabatan',
        'no_hp', 'alamat', 'sumber', 'jenis_data', 'tanggal_daftar', 'is_active'
    ];
}