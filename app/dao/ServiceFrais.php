<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 23/01/2019
 * Time: 17:31
 */

namespace App\dao;

use App\Exceptions\MonException;
use Illuminate\Database\QueryException;
use DB;
use App\metier\FicheFrais;
use App\metier\Etat;


class ServiceFrais
{
    //Sélection d'un frais sur son ID
    public function getUnFrais($id)
    {
        try {

            $response = DB::table('frais')
                ->select('id_frais', 'anneemois', 'id_visiteur', 'nbjustificatifs',
                    'datemodification', 'montantvalide', 'etat.id_etat', 'lib_etat')
                ->join('etat', 'frais.id_etat', '=', 'etat.id_etat')
                ->where('id_frais', '=', $id)
                ->first();
            return $response;
        } catch (QueryException $e) {
            throw new MonException($e->getMessage());
        }
    }


    /**
     * Liste des etats
     * @return string|\listeEtat : collection de Etats
     **/

    public function getListeEtat()
    {
        try {
            $response = DB::table('Etat')
                ->select('id_etat', 'lib_etat')
                ->orderBy('lib_etat')
                ->get();
            return $response;
        } catch (QueryException $e) {
            throw new MonException($e->getMessage());
        }
    }

    /**
     * Liste des fiches frais selon le monant
     * @return string|\listeFicheFrais : collection de FicheFrais
     **/

    public function getListeFicheFraisMontant($v1, $v2)
    {
        try {
            $response = DB::table('frais')
                ->select('id_frais', 'anneemois', 'id_visiteur', 'nbjustificatifs',
                    'datemodification', 'montantvalide', 'etat.id_etat', 'lib_etat')
                ->join('etat', 'frais.id_etat', '=', 'etat.id_etat')
                ->whereBetween('montantvalide', [$v1, $v2])
                ->orderBy('montantvalide')
                ->get();
            return $response;
        } catch (QueryException $e) {
            throw new MonException($e->getMessage());
        }
    }

    /**
     * Liste des fiches frais d'un visiteur
     * @return string|\listeFicheFrais : collection de FicheFrais
     **/

    public static function getListeFicheFrais($idVisiteur)
    {
        try {
            $response = DB::table('frais')
                ->select('id_frais', 'anneemois', 'id_visiteur', 'nbjustificatifs',
                    'datemodification', 'montantvalide', 'etat.id_etat', 'lib_etat')
                ->join('etat', 'frais.id_etat', '=', 'etat.id_etat')
                ->where('id_visiteur', '=', $idVisiteur)
                ->orderBy('id_frais')
                ->get();
            return $response;
        } catch (QueryException $e) {
            throw new MonException($e->getMessage());
        }
    }
    /**
     * Lecture de toutes les fiches de Frais
     * d'un visiteur dont l'id est passé en paramètre
     * @param type $id_visiteur id du visiteur
     * @return type Collection de Frais
     */
    // on affichera seulement la période
    public function getFraisPeriode($idVisiteur)
    {
        try {
            $response = DB::table('frais')
                ->select('id_frais', 'anneemois', 'id_visiteur', 'nbjustificatifs',
                    'datemodification', 'montantvalide', 'etat.id_etat', 'lib_etat')
                ->join('etat', 'frais.id_etat', '=', 'etat.id_etat')
                ->where('id_visiteur', '=', $idVisiteur)
                ->orderBy('id_frais')
                ->get();

            return $response;
        } catch (QueryException $e) {
            throw new MonException($e->getMessage(), 5);
        }
    }

    /**
     * Mise à jour d'une fiche de Frais
     * @param type $id_frais : id de la fiche de Frais à modifier
     * @param type $anneemois : période de la fiche de Frais à modifier
     * @param type $nbjustificatifs : Nb justificatifs de la fiche de Frais à modifier
     */
    public function updateFrais($id_frais, $anneemois, $dateModification, $montantValide,
                                $nbjustificatifs, $idVisiteur, $idetat)
    {
        try {
            $dateJour = date("Y-m-d");
            DB::table('frais')->where('id_frais', '=', $id_frais)
                ->update(['anneemois' => $anneemois, 'nbjustificatifs' => $nbjustificatifs,
                    'datemodification' => $dateModification,
                    'id_etat' => $idetat,
                    'id_visiteur' => $idVisiteur, 'montantValide' => $montantValide
                ]);
            $response = array(
                'status_message' => 'Modification réalisée'
            );
            return $response;
        } catch (QueryException $e) {
            throw new MonException($e->getMessage(), 5);
        }
    }

    /**
     * Ajout d'une fiche de Frais
     * @param type $anneemois : période de la fiche de Frais à ajouter
     * @param type $nbjustificatifs : Nb justificatifs de la fiche de Frais à ajouter
     * @param type $id_visiteur : id du visiteur de la fiche de Frais à ajouter
     */
    public function insertFrais($anneemois, $dateModification, $montantValide,
                                $nbjustificatifs, $idVisiteur, $etat)
    {
        try {
            $dateJour = date("Y-m-d");
            DB::table('frais')->insert(
                ['id_etat' => $etat, 'anneemois' => $anneemois,
                    'nbjustificatifs' => $nbjustificatifs,
                    'datemodification' => $dateModification, 'id_visiteur' => $idVisiteur,
                    'montantvalide' => $montantValide]
            );
            $response = array(
                'status_message' => 'Insertion réalisée'
            );
            return $response;
        } catch (QueryException $e) {
            throw new MonException($e->getMessage(), 5);
        }
    }

    /**
     * Suppression d'une fiche de Frais
     * @param type $id_frais : Id de la fiche de frais à supprimer
     */
    public function deleteFrais($id_frais)
    {
        try {
            DB::table('frais')->where('id_frais', '=', $id_frais)->delete();
            $response = array(
                'status_message' => 'Suppression réalisée'
            );
            return $response;
        } catch (QueryException $e) {
            throw new MonException($e->getMessage(), 5);
        }
    }

    public function getNbFraisParVisiteur($seuil)
    {
        try {
            $response = DB::table('Visiteur')
                ->select('visiteur.id_visiteur', 'nom_visiteur', DB::raw('count(*) as nb'))
                ->join('frais', 'frais.id_visiteur', '=', 'visiteur.id_visiteur')
                ->groupBy('visiteur.id_visiteur', 'nom_visiteur')
                ->havingRaw('count(*) >='.$seuil)
                ->get();

            return $response;
        } catch
        (QueryException $e) {
            throw new MonException($e->getMessage(), 5);
        }

    }


public function getVisiteurFraisMax()
{
    /*
    select visiteur.nom_visiteur
    from visiteur join frais o
    n visiteur.id_visiteur = frais.id_visiteur
    where montantvalide = (select montantvalide
    from frais)
     */
    try {
        $response = DB::table('visiteur')
            ->select('nom_visiteur')
            ->join('frais', 'frais.id_visiteur', '=', 'visiteur.id_visiteur')
            ->orwhere('montantvalide','=',function($query)
             {   $query->from('frais')
                 ->selectRaw('max(montantvalide)')
                 ->get();
            })
            ->get();
        return $response;
    } catch  (QueryException $e) {
        throw new MonException($e->getMessage(), 5);
    }
}






/**
* on valide le montant des frais hors forfait
     * @param type $id_frais : Id de la fiche de frais à supprimer
     */
    public function vallidateMontant($id_frais,$montantHF)
    {
        try {
            DB::table('frais')->where('id_frais', '=', $id_frais)
                ->update(['montantValide' => $montantHF]);
            $response = array(
                'status_message' => 'Modification réalisée'
            );
            return $response;
        } catch (QueryException $e) {
            throw new MonException($e->getMessage(), 5);
        }
    }

}
