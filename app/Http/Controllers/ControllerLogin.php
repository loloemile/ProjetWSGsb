<?php


namespace App\Http\Controllers;

use App\dao\ServiceLogin;
use Illuminate\Support\Facades\Hash;

class ControllerLogin extends Controller
{

    public function signIn()
    {
        try {
            $json = file_get_contents('php://input'); // Récupération du flux JSON
            $visiteurJson = json_decode($json);
            if ($visiteurJson != null) {
                $login_visiteur = $visiteurJson->login_visiteur;
                $pwd_visiteur = $visiteurJson->pwd_visiteur;
                $unService = new ServiceLogin();
                $visiteur = $unService->getConnexion($login_visiteur, $pwd_visiteur);
                return json_encode($visiteur);
            }
        } catch (MonException $e) {
            $erreur = $e->getMessage();
            return response()->json($erreur);
        }
    }


    public function updatePassword($pwd)
    {
        $newpwd = Hash::make($pwd);
        try {
            $unLogin = new ServiceLogin();
            $unLogin->miseAjourMotPasse($newpwd);
            return view('home');
        } catch (MonException $e) {
            $erreur = $e->getMessage();
            return view('Error', compact('erreur'));
        } catch (Exception $e) {
            $erreur = $e->getMessage();
            return view('Error', compact('erreur'));
        }
    }
}
