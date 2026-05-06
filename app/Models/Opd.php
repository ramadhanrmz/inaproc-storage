<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Opd extends Model
{
    use HasFactory;

    protected $fillable = ['nama'];

    public function accounts()
    {
        return $this->hasMany(InaprocAccount::class);
    }
}
