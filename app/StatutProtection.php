<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatutProtection extends Model
{
    use HasFactory;

    protected $connection = 'pram';
    protected $table = 'statut_protections';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'nom_interne'];

    public function mares()
    {
        return $this->hasMany(Mare::class);
    }
}
