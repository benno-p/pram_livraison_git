<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigLogoPartenaire extends Model
{
    use HasFactory;

    protected $connection = 'pram';
    protected $table = 'config_logo_partenaires';
    protected $primaryKey = 'id';
    protected $fillable = ['nom', 'logo', 'site_url'];
}
