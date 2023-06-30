<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsageDechet extends Model
{
    protected $connection = 'pram';
    protected $table = 'usage_dechets';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
    	return $this->belongsToMany(Fiche::class);
    }

}
