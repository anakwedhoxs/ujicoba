<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventaris extends Model
{
    use HasFactory;
    
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'Kategori',
        'Merk',
        'Seri',
    ];

    public function sows(): HasMany
    {
        return $this->hasMany(sow::class);
    }
    
}
