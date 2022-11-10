<?php


namespace App\Http\Controllers;


use App\dao\ServiceFrais;
use App\dao\ServiceFraisHorsForfait;
use App\Exceptions\MonException;

class ControllerFraisHorsForfait extends Controller

{
    /**
     * Affiche la liste de tous les Frais Hors Forfait
     * d'un fiche de Frais
     * @param type $id_frais Id de la fiche de Frais dont
     * on veut la liste des FHF
     * @return type Vue listeFraisHorsForfait
     */
    public function getListeFraisHorsForfait($id_frais)
    {
        try {
            $unSHF = new ServiceFraisHorsForfait();
            $mesFraisHff = $unSHF->getListeFraisHorsForfait($id_frais);
            return json_encode($mesFraisHff);

        } catch (MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur, 204);
        }
    }

    /**
     * Initialise le formulaire d'un Frais Hors Forfait pour un ajout
 */
    public function addFraisHorsForfait()
    {

        try {
            $json = file_get_contents('php://input'); // Récupération du flux JSON
            $fraisJson = json_decode($json);
            if ($fraisJson != null) {
                $id_frais = $fraisJson->id_frais;
                $date = $fraisJson->date_fraishorsforfait;
                $montant = $fraisJson->montant_fraishorsforfait;
                $libelle = $fraisJson->lib_fraishorsforfait;

                $unSHF = new ServiceFraisHorsForfait();
                $uneReponse = $unSHF->insertFraisHorsForfait($id_frais, $date, $montant, $libelle);
                return response()->json($uneReponse);
            }
        } catch (MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur, 201);
        }

    }


    public function getUnFraishorsforfait($id_fraishf)
    {
        try {
            $unSHF = new ServiceFraisHorsForfait();
            $unFraisHff = $unSHF->getById($id_fraishf);
            return json_encode($unFraisHff);

        } catch (MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur, 204);
        }
    }


    /**
     * Initialise le formulaire d'un Frais Hors Forfait pour la modification
     * Lit le FHF surson id passé en paramètre
     * Initialise le titre du formulaire
     * @param type $id Id du FHF à modifier
     * @return type Vue formFraisHorsForfait
     */
    public function updateFicheFraisHF()
    {
        try {
            $json = file_get_contents('php://input'); // Récupération du flux JSON
            $fraisJson = json_decode($json);
            if ($fraisJson != null) {
                $id_fraishorsforfait = $fraisJson->id_fraishorsforfait;
                $date = $fraisJson->date_fraishorsforfait;
                $montant = $fraisJson->montant_fraishorsforfait;
                $libelle = $fraisJson->lib_fraishorsforfait;
                $unSHF = new ServiceFraisHorsForfait();
                $uneReponse = $unSHF->updateFraisHorsForfait($id_fraishorsforfait, $date, $montant, $libelle);
                return response()->json($uneReponse);
            }
        } catch (MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur, 201);
        }
    }


    /**
     * Supression d'un frais hors forfait sur son Id
     * On passe aussi l'id de la fiche de Frais pour pouvoir
     * retourner à la liste des FHF de la fiche Frais après suppression
     * On utilise le gestionnaire d'exception même si on n'en a pas besoin
     * car il n'y a pas de contrainte, mais simplement pour illuster let
     * fonctionnemende la gestion du retour
     * @param type $id_fraishorsforfait Id du FHF à supprimer
     * @return type Vue getListeFraisHorsForfait ou getErrors
     */
    public function deleteFicheFraisHF()
    {
        try {
            $json = file_get_contents('php://input'); // Récupération du flux JSON
            $fraisJson = json_decode($json);
            if ($fraisJson != null) {
                $id_fraishorsforfait = $fraisJson->id_fraishorsforfait;
                $unSHF = new ServiceFraisHorsForfait();
                $uneReponse = $unSHF->deleteFraisHorsForfait($id_fraishorsforfait);
                return response()->json($uneReponse);
            }

        } catch (MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur, 201);

        }

    }
}
