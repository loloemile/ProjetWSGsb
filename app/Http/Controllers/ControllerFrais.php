<?php
/**
 * Created by PhpStorm.
 * User: christian
 * Date: 25/01/2019
 * Time: 14:10
 */

namespace App\Http\Controllers;

use App\dao\ServiceFrais;
use App\dao\ServiceFraisHorsForfait;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\metier\FicheFrais;
use App\metier\Etat;
use App\Exceptions\MonException;
use Illuminate\Http\Request;


class ControllerFrais extends Controller
{

    // visualise les frais d'un visiteur
    public function getListeFicheFrais($id)
    {
        $listeFicheFrais = array();
        try {
            $unService = new ServiceFrais();
            $response = $unService->getListeFicheFrais($id);
            return json_encode($response);
        } catch (MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur, 204);
        }
    }


    // visualise les frais selon les montants
    // en mode path
    public function getListeFicheFraisMontant($v1,$v2)
    {
        $listeFicheFrais = array();
        try {
            $unService = new ServiceFrais();
            $response = $unService->getListeFicheFraisMontant($v1,$v2);

            return json_encode($response);
        } catch (MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur, 204);
        }
    }




// visualise les Etats
    // en mode query
    public function getListeEtat()
    {

        try {
            $unService = new ServiceFrais();
            $response = $unService->getListeEtat();
            return json_encode($response);
        } catch (MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur, 204);
        }
    }
    // visualise les frais selon les montants
    // en mode query
    public function getListeFicheFraisMontantQuery(Request $req)
    {
        $v1=$req->input('valeur1');
        $v2=$req->input('valeur2');
        try {
            $unService = new ServiceFrais();
            $response = $unService->getListeFicheFraisMontant($v1,$v2);
            return json_encode($response);
        } catch (MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur, 204);
        }
    }


    // visualise les frais d'une période d'un visiteur
    public function getListeFraisPeriode($id)
    {
        try {
            $unService = new ServiceFrais();
            $response = $unService->getFraisPeriode($id);
            return response()->json($response);
        } catch (MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur, 204);
        }
    }

// visualise les frais d'une période d'un visiteur
    public function getUnFrais($id)
    {
        try {
            $unService = new ServiceFrais();
            $response = $unService->getUnFrais($id);


            return json_encode($response);

        } catch (MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur, 204);
        }
    }

    /**
     * modifie  un  frais
     * @param type $json
     * @return \
     * @throws Exception
     */
    public function updateFicheFrais()
    {
        try {
            $json = file_get_contents('php://input'); // Récupération du flux JSON
            $fraisJson = json_decode($json);
            if ($fraisJson != null) {
                $idfrais = $fraisJson->id_frais;
                $anneeMois = $fraisJson->anneemois;
                $dateModification = $fraisJson->datemodification;
                $montantValide = $fraisJson->montantvalide;
                $nbJustificatifs = $fraisJson->nbjustificatifs;
                $idVisiteur = $fraisJson->id_visiteur;
                $id_etat = $fraisJson->id_etat;
                $unService = new ServiceFrais();
                $uneReponse = $unService->updateFrais($idfrais,$anneeMois, $dateModification,
                    $montantValide,$nbJustificatifs, $idVisiteur, $id_etat);
                return response()->json($uneReponse);
            }

        } catch(MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur, 201);
        }
    }

    /**
     * ajoute un nouveau  frais
     * @param type $json
     * @return \
     * @throws Exception
     */
    public function addFicheFrais()
    {
        try {
            $json = file_get_contents('php://input'); // Récupération du flux JSON
            $fraisJson = json_decode($json);
            if ($fraisJson != null) {
                $anneeMois = $fraisJson->anneemois;
                $dateModification = $fraisJson->datemodification;
                $montantValide = $fraisJson->montantvalide;
                $nbJustificatifs = $fraisJson->nbjustificatifs;
                $idVisiteur = $fraisJson->id_visiteur;
                $etat = $fraisJson->id_etat;
                $unService = new ServiceFrais();
                $uneReponse = $unService->insertFrais($anneeMois, $dateModification,
                    $montantValide,
                    $nbJustificatifs, $idVisiteur, $etat);
                return response()->json($uneReponse);
            }
        } catch(MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur, 201);
        }
    }



    /**
     * supprime un frais
     * @param type $json
     * @return \
     * @throws Exception
     */
    public function suppressionFrais()
    {
        try {
            $json = file_get_contents('php://input'); // Récupération du flux JSON
            $fraisJson = json_decode($json);
            if ($fraisJson != null) {
                $idfrais = $fraisJson->id;
                $unService = new ServiceFrais();
                $uneReponse = $unService->deleteFrais($idfrais);
                return response()->json($uneReponse);
            }
        } catch (MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur, 201);
        }
    }

    /**
     * Frais par visiteur
     * @param type $json
     * @return \
     * @throws Exception
     */

    public function getListeFraisVisiteur($seuil)
    {
        $listeItems = array();
        try {
            $unService = new ServiceFrais();
            $response = $unService->getNbFraisParVisiteur($seuil);
            foreach ($response as $value) {
               $item = array();
                $item["id_visiteur"] = $value->id_visiteur;
                $item["nom_visiteur"] = (string)$value->nom_visiteur;
                $item["nb"] = $value->nb;
                $listeItems[] = $item;
            }

            return json_encode($listeItems);
        } catch (MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur, 204);
        }
    }


    public function getVisiteurFraisMax()
    {
        try {
            $unService = new ServiceFrais();
            $response = $unService->getVisiteurFraisMax();
           return json_encode($response);
        } catch (MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur, 204);
        }
    }

    /**
     * mise à jour d'un frais hors forfait sur son Id
     * On passe aussi l'id de la fiche de Frais pour pouvoir
     * mettre à jour le montant de à la liste des FHF de la fiche Frais
     * On utilise le gestionnaire d'exception même si on n'en a pas besoin
     * car il n'y a pas de contrainte, mais simplement pour illuster let
     * fonctionnemende la gestion du retour
     */
    public function validateFraisMontant()
    {
        try {
            $json = file_get_contents('php://input'); // Récupération du flux JSON
            $fraisJson = json_decode($json);
            if ($fraisJson != null) {
                $id_frais = $fraisJson->id_frais;
                $montantHF = $fraisJson->montantHF;
                $unSF = new ServiceFrais();
                $uneReponse = $unSF->vallidateMontant($id_frais,$montantHF);
                return response()->json($uneReponse);
            }

        } catch (MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur, 201);

        }

    }


}
