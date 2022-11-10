<?php

namespace App\Http\Controllers;

use App\Exceptions\MonException;
use Request;
use Session;
use App\dao\ServiceFraisHorsForfait;
use App\Http\Metier\Frais;


class FraisHorsForfaitController extends Controller

{
    /**
     * Affiche la liste de tous les Frais Hors Forfait
     * d'un fiche de Frais
     * @param type $id_frais Id de la fiche de Frais dont
     * on veut la liste des FHF
     * @return type Vue listeFraisHorsForfait
     */
    public function getListeFraisHorsForfait($id_frais) {
        try {
            $erreur = Session::get('erreur');
            $unFrais = new ServiceFraisHorsForfait();
            $id_visiteur = Session::get('id');
            $mesFraisHff = $unFrais->getListeFraisHorsForfait($id_frais);
            $montantTotal = 0;
            $FraisID = $id_frais;

            foreach($mesFraisHff as $unFraisHff) {
                $montantTotal += $unFraisHff->montant_fraishorsforfait;
            }

            return view('Vues/listeFraisHorsForfait', compact('mesFraisHff', 'erreur', 'montantTotal', 'FraisID'));
        } catch (MonException $e) {
            $monErreur = $e->getMessage();
            return view('Vues/pageErreur', compact('monErreur'));
        } catch (Exception $e) {
            $monErreur = $e->getMessage();
            return view('Vues/pageErreur', compact('monErreur'));
        }
    }

    /**
     * Enregistre une modification ou un ajout
     * Récupère les données saisies dans les INPUT
     * Si l'id > 0 c'est une modification, sinon c'est un ajout
     * Réaffiche la liste des FHF de la fiche de Frais
     * @return type route /getListeFraisHorsForfait
     */
    public function validateFraisHorsForfait($id_frais) {
        try {
            $id_fraishorsforfait = Request::input('id_fraishorsforfait');
            $date = Request::input('date_fraishorsforfait');
            $montant = Request::input('montant_fraishorsforfait');
            $libelle = Request::input('lib_fraishorsforfait');
            $unFraisHFF = new ServiceFraisHorsForfait();
            $id_frais = Request::input('id_frais');
            if ($id_fraishorsforfait > 0) {
                $unFraisHFF->updateFraisHorsForfait($id_fraishorsforfait, $date, $montant, $libelle);
            } else {
                $unFraisHFF->insertFraisHorsForfait($id_frais, $date, $montant, $libelle);
            }

            return redirect('/getListeHorsForfait/'.$id_frais);


        } catch (MonException $e) {
            $monErreur = $e->getMessage();
            return view('Vues/pageErreur', compact('monErreur'));
        } catch (Exception $e) {
            $monErreur = $e->getMessage();
            return view('Vues/pageErreur', compact('monErreur'));
        }
    }

    /**
     * Initialise le formulaire d'un Frais Hors Forfait pour la modification
     * Lit le FHF surson id passé en paramètre
     * Initialise le titre du formulaire
     * @param type $id Id du FHF à modifier
     * @return type Vue formFraisHorsForfait
     */
    public function updateFraisHorsForfait($id_fraisHff) {
        try {
            $erreur = "";
            $unServiceFraisHff = new ServiceFraisHorsForfait();
            $unFraisHFF = $unServiceFraisHff->getById($id_fraisHff);
            $titreVue = "Modification d'une fiche de Frais";
            return view('Vues/formFraisHorsForfait', compact('unFraisHFF', 'titreVue', 'erreur'));
        } catch (MonException $e) {
            $monErreur = $e->getMessage();
            return view('Vues/pageErreur', compact('monErreur'));
        } catch (Exception $e) {
            $monErreur = $e->getMessage();
            return view('Vues/pageErreur', compact('monErreur'));
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
    public function supprimeFraisHorsForfait($id_fraisHff, $id_frais) {
        $unServiceFraisHff = new ServiceFraisHorsForfait();
        try {
            $unServiceFraisHff->deleteFraisHorsForfait($id_fraisHff);
        } catch (Exception $e) {
            Session::put('monmonErreur', $e->getMessage());
        } finally {
            return redirect('/getListeFraisHorsForfait/'.$id_frais);
        }
    }
    /**
     * Initialise le formulaire d'un Frais Hors Forfait pour un ajout
     * Instancie un FHF et lui affecte l'id de la fiche de Frais
     * dont il dépend. Cet id sera placé dans un INPUT HIDDEN
     * Initialise le titre du formulaire
     * @param type $id Id de la fiche de Frais
     * @return type Vue formFraisHorsForfait
     */
    public function addFraisHorsForfait($id_frais) {
        try {
            $erreur = "";
            $unFrais = new Frais();
            $unFrais->id_frais = $id_frais;
            $titreVue = "Ajout d'une fiche de Frais Hors Forfait";
            return view('Vues/formFraisHorsForfait', compact('unFrais', 'titreVue', 'erreur'));
        } catch (MonException $e) {
            $monErreur = $e->getMessage();
            return view('Vues/pageErreur', compact('monErreur'));
        } catch (Exception $e) {
            $monErreur = $e->getMessage();
            return view('Vues/pageErreur', compact('monErreur'));
        }
    }



}
