<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ControllerFraisHorsForfait;
use App\Http\Controllers\ControllerFrais;
use App\Http\Controllers\ControllerLogin;
use App\Http\Middleware\Cors;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/getConnexion',  [App\Http\Controllers\ControllerLogin::class, 'signIn'])->middleware('cors');

Route::get('/updatePassword/{pwd}',  [App\Http\Controllers\ControllerLogin::class, 'updatePassword'])->middleware('cors');

Route::prefix('frais')->group(function () {


    Route::get('listeFrais/{id}', [App\Http\Controllers\ControllerFrais::class, 'getListeFicheFrais'])->middleware('cors');
    Route::get('/listeFraisMontant/{v1}/{v2}', [ControllerFrais::class, 'getListeFicheFraisMontant'])->middleware('cors');
    Route::get('listeFraisPeriode/{id}', [App\Http\Controllers\ControllerFrais::class, 'getListeFraisPeriode'])->middleware('cors');
    Route::get('/listeFraisMontantQuery', [ControllerFrais::class, 'getListeFicheFraisMontantQuery'])->middleware('cors');
    Route::get('/listeEtat', [ControllerFrais::class, 'getListeEtat'])->middleware('cors');

    Route::get('/getVisiteurFraisMax', [ControllerFrais::class, 'getListeFraisVisiteur'])->middleware('cors');
    Route::get('/getListeFraisVisiteur/{seuil}', [ControllerFrais::class, 'getVisiteurFraisMax'])->middleware('cors');

    Route::get('/getUnFrais/{id}', [ControllerFrais::class, 'getUnFrais'])->middleware('cors');
    Route::get('/listeFraisPeriode/{id}', [ControllerFrais::class, 'getListeFraisPeriode'])->middleware('cors');

    Route::post('/updateFicheFrais', [ControllerFrais::class, 'updateFicheFrais'])->middleware('cors');
    Route::post('/addFicheFrais', [ControllerFrais::class, 'addFicheFrais'])->middleware('cors');
    Route::post('/deleteFicheFrais', [ControllerFrais::class, 'suppressionFrais'])->middleware('cors');

    Route::get('/getListeHorsForfait/{id}', [ControllerFraisHorsForfait::class ,'getListeFraisHorsForfait']);

    Route::post('/addFraisHorsForfait', [ControllerFraisHorsForfait::class ,'addFraisHorsForfait']);
    Route::get('/getUnFraishorsforfait/{id}', [ControllerFraisHorsForfait::class ,'getUnFraishorsforfait']);
    Route::post('/updateFicheFraisHF', [ControllerFraisHorsForfait::class ,'updateFicheFraisHF']);
    Route::post('/deleteFicheFraisHF', [ControllerFraisHorsForfait::class ,'deleteFicheFraisHF']);
    Route::post('/validateFraisMontant', [ControllerFrais::class ,'validateFraisMontant']);


});

Route::get('/miseajour/{pwd}', [App\Http\Controllers\ControllerLogin::class, 'updatePassword']);


