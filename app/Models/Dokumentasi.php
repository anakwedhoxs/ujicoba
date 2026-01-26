<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dokumentasi extends Model
{
    use HasFactory;

    protected $table = 'dokumentasis'; // opsional (Laravel sebenarnya sudah auto)

    protected $fillable = [
        'nama_barang',
        'foto',
    ];
}
