<?php

namespace App\metier;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use DB;

class FicheFrais extends Model implements \JsonSerializable
{

//On déclare la table Fichefrais
    protected $table = 'FicheFrais';
    protected $fillable = [
        'id_frais',
        'anneeMois',
        'dateModification',
        'montantValide',
        'nbJustificatifs',
        'id_visiteur',
        'etat'
    ];
// On peut ajouter ou modifier des données
    public $timetamps = true;

}
