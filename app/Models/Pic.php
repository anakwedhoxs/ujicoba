<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Pic extends Model
{
    protected $fillable = ['nama'];


    public function sows(): HasMany
    {
        return $this->hasMany(Sow::class);
    }
}





