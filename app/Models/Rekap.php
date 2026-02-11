<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class Rekap extends Model
{
    protected $table = 'rekaps';


    protected $fillable = [
        'kategori',
        'merk',
        'seri',
        'jumlah',
    ];


    protected $casts = [
        'jumlah' => 'integer',
    ];

    protected static function booted()
{
    static::saved(function ($rekap) {
        if ($rekap->jumlah <= 0) {
            $rekap->delete();
        }
    });
}


}

