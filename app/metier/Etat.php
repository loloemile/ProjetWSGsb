<?php

namespace App\metier;

use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use DB;
class Etat extends Model {

    protected $table = 'Etat';
    protected $fillable = [
        'id_etat',
        'lib_etat'
    ];
    // On peut ajouter ou modifier des données
    public $timetamps = true;



}