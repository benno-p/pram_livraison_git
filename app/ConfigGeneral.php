<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfigGeneral extends Model
{
    use HasFactory;

    protected $connection = 'pram';
    protected $table = 'config_generals';
    protected $primaryKey = 'id';
    protected $fillable = [
        'titre_image_accueil',
        'titre_accueil',
        'texte_accueil',
        'image_page_accueil',
        'logo',
        'redirect_url_logo',
        'path_region_geojson',
        'centroide_x_l93',
        'centroide_y_l93',
        'centroide_lng',
        'centroide_lat'
    ];
}
