<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 01/10/2019
 * Time: 13:46
 */

namespace App\dao;

use App\Exceptions\MonException;
use Illuminate\Support\Facades\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use DB;
use App\metier\Visiteur;
use App\metier\Frais;
use App\metier\FraisHorsForfait;

class ServiceFraisHorsForfait
{
    public function getById($id_frais) {
        try {
            $unFraishf = DB::table('fraishorsforfait')
                ->Select()
                ->where('fraishorsforfait.id_fraishorsforfait', '=', $id_frais)
                ->first();
            return $unFraishf;
        } catch (QueryException $e) {
            throw new MonException($e->getMessage(), 5);
        }
    }



    public function getListeFraisHorsForfait($id_frais) {
        try {
            $mesFraisHF = DB::table('fraishorsforfait')
                ->Select()
                ->where('fraishorsforfait.id_frais', '=', $id_frais)
                ->get();
            return $mesFraisHF;
        } catch (QueryException $e) {
            throw new MonException($e->getMessage(), 5);
        }
    }

    public function updateFraisHorsForfait($id_fraishorsforfait, $date, $montant, $libelle) {
        try {
            DB::table('fraishorsforfait')
                ->where('id_fraishorsforfait', '=', $id_fraishorsforfait)
                ->update(['date_fraishorsforfait' => $date,
                    'montant_fraishorsforfait' => $montant,
                    'lib_fraishorsforfait' => $libelle
                ]);
        } catch (QueryException $e) {
            throw new MonException($e->getMessage(), 5);
        }
    }

    public function deleteFraisHorsForfait($id_fraishf) {
        try {
            DB::table('fraishorsforfait')->where('id_fraishorsforfait', '=', $id_fraishf)->delete();
        } catch (QueryException $e) {
            throw new MonException($e->getMessage(), 5);
        }
    }

    public function insertFraisHorsForfait($id_frais, $date, $montant, $libelle) {
        try {
            DB::table('fraishorsforfait')->insert(
                [ 'id_frais' => $id_frais,
                    'date_fraishorsforfait' => $date,
                    'montant_fraishorsforfait' => $montant,
                    'lib_fraishorsforfait' => $libelle
                ]
            );
        } catch (QueryException $e) {
            throw new MonException($e->getMessage(), 5);
        }
    }
}
