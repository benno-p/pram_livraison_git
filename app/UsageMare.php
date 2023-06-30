<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsageMare extends Model
{
    protected $connection = 'pram';
    protected $table = 'usage_mares';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->belongsToMany(Fiche::class);
    }
}
