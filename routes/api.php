<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\FavorisControrller;
use App\Http\Controllers\Api\AvisController;
use App\Http\Controllers\Api\BilletController;
use App\Http\Controllers\Api\PaymentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

//Ecrit par jean-yves
Route::middleware('auth:sanctum')->group(function () {
    // Route de déconnexion
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function(Request $request) {
        return $request->user();
    });

    //Favoris du user connecté
    Route::post('/favoris', [FavorisControrller::class, 'addFavoris']);
    Route::delete('/favoris/{id}', [FavorisControrller::class, 'removeFavoris']);
    Route::get('/favoris', [FavorisControrller::class, 'listFavoris']);

    //Avis d'un user et d'un évènement
    Route::post('/avis', [AvisController::class, 'store']);
    Route::delete('/avis/{id}', [AvisController::class, 'destroy']);
    Route::get('/evenement/{id}/avis', [AvisController::class, 'index']);

    //Billet d'un évènement
    Route::get('/evenement/{id}/billets', [BilletController::class, 'getBilletsByEvenement']);

    //Commande et ticket
    Route::post('/generate/paid/ticket', [BilletController::class, 'createOrderAndGenerateTickets']);
    Route::post('/generate/free/ticket', [BilletController::class, 'generateFreeTicketForEvent']);
    Route::get('/getUsertickets', [BilletController::class, 'getUserTicketsWithDetails']);
    Route::put('/refund/ticket', [BilletController::class, 'refundTicket']);

});

//Register
Route::post('/register', [AuthController::class, 'register']);
//Login
Route::post('/login', [AuthController::class, 'login']);
//Afficher les categories d'évènements
Route::get('/categories', [EventController::class, 'getCategories']);
//Renvoyer les évènements disponibles
Route::get('/events', [EventController::class, 'getAllEvents']);
//Faire le trie par categorie
Route::get('/events/category/{categoryId}', [EventController::class, 'getEventsByCategory']);
//Renvoyer les èvènement à venir
Route::get('events/top', [EventController::class, 'getBestEvents']);
//Renvoyer les billets d'un évènement
Route::get('/eventBillet/{id}', [BilletController::class, 'getEvenementBillets']);
