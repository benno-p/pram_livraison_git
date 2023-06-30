<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HydrologiePresencePoisson extends Model
{
    use HasFactory;

    protected $connection = 'pram';
    protected $table = 'hydrologie_presence_poissons';
    protected $primaryKey = 'id';
    protected $fillable = ['nom'];

    public function fiches()
    {
        return $this->hasMany(Fiche::class);
    }
}
