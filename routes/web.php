<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\CatEventController;
use App\Http\Controllers\Admin\TypeBilletController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BilletController;
use App\Http\Controllers\Organizer\MyEventController;
use App\Http\Controllers\Organizer\MyBilletController;
use App\Http\Controllers\Organizer\MyProfileController;
use App\Http\Controllers\Organizer\MyEventNoticeController;
use App\Http\Controllers\Admin\OtherController;
use App\Http\Controllers\Organizer\MyEventOderController;


/*
|------------------------------------------------------------use App\Http\Controllers\Admin\EventController;
--------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

/*Route::get('/', function () {
    return view('pages.index');
})->name('index');*/

/*Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');*/


/*Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});*/

//By jean-yves
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->middleware('check.role:admin')->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->middleware('check.role:admin')->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->middleware('check.role:admin')->name('profile.destroy');
});

require __DIR__.'/auth.php';

//Ecrit par jean-yves
Route::get('/dashboard', [HomeController::class, 'index'])
    ->middleware(['auth', 'verified','checkRole:admin,organizer'])
    ->name('dashboard');

// 1 : Route accessible par les administrateurs
Route::middleware(['auth', 'verified', 'checkRole:admin'])->group(function () {
    //Evenement
    Route::get('/evenement', [EventController::class, 'eventList'])->middleware('checkRole:admin')->name('event');
    Route::get('/evenement/formulaire-details-evenement/{id}', [EventController::class, 'indexShowEvent'])->middleware('checkRole:admin')->name('form-showEvent');
    Route::get('/evenement/formulaire-details-evenement/approuvé/{id}', [EventController::class, 'approvedEvent'])->middleware('checkRole:admin')->name('approvedEvent');
    Route::patch('evenement/formulaire-details-evenement/desapprouvé/{id}', [EventController::class, 'disapprovedEvent'])->middleware('checkRole:admin')->name('desapprovedEvent');
    //Route::get('/evenement/formulaire-details-evenement/désapprouvé/{id}', [EventController::class, 'disapprovedEvent'])->middleware('checkRole:admin')->name('desapprovedEvent');

    //Categorie d'évènement
    Route::get('/categorie-evenement', [CatEventController::class, 'catEventList'])->middleware('checkRole:admin')->name('categorie');
    Route::get('/categorie-evenement/formulaire-ajout-categorie', [CatEventController::class, 'indexCatEvent'])->middleware('checkRole:admin')->name('form-categorie');
    Route::post('/categorie-evenement/formulaire-ajout-categorie/store', [CatEventController::class, 'storeCatEvent'])->middleware('checkRole:admin')->name('storeCatEvent');
    Route::get('/categorie-evenement/formulaire-mise-a-ajout-categorie/{id}', [CatEventController::class, 'indexUpdateCatEvent'])->middleware('checkRole:admin')->name('form-updateCatEvent');
    Route::put('/categorie-evenement/formulaire-mise-a-ajout-categorie/update/{id}', [CatEventController::class, 'updateCatEvent'])->middleware('checkRole:admin')->name('updateCatEvent');
    Route::delete('/categorie-evenement/supprimer-categorie/{id}', [CatEventController::class, 'deleteCatEvent'])->middleware('checkRole:admin')->name('deleteCatEvent');

    //Billet
    Route::get('/billet', [BilletController::class, 'billetList'])->middleware('checkRole:admin')->name('billet');
    Route::get('/billet/formulaire-details-billet/{id}', [BilletController::class, 'indexShowBillet'])->middleware('checkRole:admin')->name('form-showBillet');

    //Type de billet
    Route::get('/type-billet', [TypeBilletController::class, 'typeBilletList'])->middleware('checkRole:admin')->name('type');
    Route::get('/type-billet/formulaire-ajout-type', [TypeBilletController::class, 'indexTypeBillet'])->middleware('checkRole:admin')->name('form-type');
    Route::post('/type-billet/formulaire-ajout-type/store', [TypeBilletController::class, 'storeTypeBillet'])->middleware('checkRole:admin')->name('storeTypeBillet');
    Route::get('/type-billet/formulaire-mise-a-ajout-type/{id}', [TypeBilletController::class, 'indexUpdateTypeBillet'])->middleware('checkRole:admin')->name('form-updateType');
    Route::put('/type-billet/formulaire-mise-a-ajout-type/update/{id}', [TypeBilletController::class, 'updateTypeBillet'])->middleware('checkRole:admin')->name('updateType');
    Route::delete('/type-billet/supprimer-type/{id}', [TypeBilletController::class, 'deleteTypeBillet'])->middleware('checkRole:admin')->name('deleteType');

    //Avis et commande
    Route::get('/avis', [OtherController::class, 'eventNoticeList'])->middleware('checkRole:admin')->name('avis');
    Route::get('/commande', [OtherController::class, 'eventOrderList'])->middleware('checkRole:admin')->name('commande');


    //Utilisateur
    Route::get('/utilisateur', [UserController::class, 'userList'])->middleware('checkRole:admin')->name('user');
    Route::get('/utilisateur/formulaire-ajout-utilisateur', [UserController::class, 'indexUser'])->middleware('checkRole:admin')->name('form-user');
    Route::post('/utilisateur/formulaire-ajout-utilisateur/store', [UserController::class, 'storeUser'])->middleware('checkRole:admin')->name('storeUser');
    Route::get('/utilisateur/formulaire-mise-a-jour-utilisateur/{id}', [UserController::class, 'indexUpdateUser'])->middleware('checkRole:admin')->name('form-updateuser');
    Route::put('/utilisateur/formulaire-mise-a-jour-utilisateur/update/{id}', [UserController::class, 'updateUser'])->middleware('checkRole:admin')->name('updateUser');
    Route::get('/utilisateur/activer-utilisateur/{id}', [UserController::class, 'activateUser'])->middleware('checkRole:admin')->name('userActivate');
    Route::get('/utilisateur/desactiver-utilisateur/{id}', [UserController::class, 'desactivateUser'])->middleware('checkRole:admin')->name('userDesactivate');
    Route::put('/utilisateur/formulaire-mise-a-jour-information-admin/update/information/{id}', [UserController::class, 'updateAdminInfo'])->middleware('checkRole:admin')->name('updateAdminInfo');
    Route::put('/utilisateur/formulaire-mise-a-jour-password-admin/update/password/{id}', [UserController::class, 'updateAdminPassword'])->middleware('checkRole:admin')->name('updateAdminPassword');
});

// 2 : Route accessible par les organisateurs
Route::middleware(['auth', 'verified', 'checkRole:organizer'])->group(function () {
    //Evènement
    Route::get('/organisateur/mes-evenements', [MyEventController::class, 'myEventList'])->middleware('checkRole:organizer')->name('myEvent');
    Route::get('/organisateur/mes-billets-évènement/{eventId}', [MyEventController::class, 'getMyEventBillets'])->middleware('checkRole:organizer')->name('myEventbillets');
    Route::get('/organisateur/mes-evenements/formulaire-ajout-evenement', [MyEventController::class, 'indexMyEvent'])->middleware('checkRole:organizer')->name('form-myEvent');
    Route::post('/organisateur/mes-evenements/formulaire-ajout-evenement/store', [MyEventController::class, 'storeMyEvent'])->middleware('checkRole:organizer')->name('storeMyEvent');
    Route::get('/organisateur/mes-evenements/formulaire-mise-a-jour-evenement/{id}', [MyEventController::class, 'indexUpdateMyEvent'])->middleware('checkRole:organizer')->name('form-updateMyEvent');
    Route::put('/organisateur/mes-evenements/formulaire-mise-a-jour-evenement/update/{id}', [MyEventController::class, 'updateMyEvent'])->middleware('checkRole:organizer')->name('updateMyEvent');
    Route::delete('/organisateur/mes-evenements/supprimer-evenement/{id}', [MyEventController::class, 'deleteMyEvent'])->middleware('checkRole:organizer')->name('deleteMyEvent');
    Route::put('/organisateur/mes-évènements/publier-mon-évènement/{eventId}', [MyEventController::class, 'publishMyEvent'])->middleware('checkRole:organizer')->name('publishMyEvent');
    Route::put('/organisateur/mes-évènements/annuler-mon-évènement/{eventId}', [MyEventController::class, 'canceledEvent'])->middleware('checkRole:organizer')->name('canceledMyEvent');
    //Route::get('/event/{id}', [EventController::class, 'showMotif'])->middleware('checkRole:organizer')->name('event.showMotif');

    //Billet
    Route::get('/organisateur/mes-billets', [MyBilletController::class, 'myBilletList'])->middleware('checkRole:organizer')->name('myBillet');
    Route::get('/organisateur/mes-billets/formulaire-ajout-billet', [MyBilletController::class, 'indexMyBillet'])->middleware('checkRole:organizer')->name('form-myBillet');
    Route::post('/organisateur/mes-billets/formulaire-ajout-billet/store', [MyBilletController::class, 'storeMyBillet'])->middleware('checkRole:organizer')->name('storeMyBillet');
    Route::get('/organisateur/mes-billets/formulaire-mise-a-jour-billet/{id}', [MyBilletController::class, 'indexUpdateMyBillet'])->middleware('checkRole:organizer')->name('form-updateMyBillet');
    Route::put('/organisateur/mes-billets/formulaire-mise-a-jour-billet/update/{id}', [MyBilletController::class, 'updateMyBillet'])->middleware('checkRole:organizer')->name('updateMyBillet');
    Route::delete('/organisateur/mes-billets/supprimer-billet/{id}', [MyBilletController::class, 'deleteMyBillet'])->middleware('checkRole:organizer')->name('deleteMyBillet');
    Route::get('/organisateur/scanner-ticket', [MyBilletController::class, 'scannerPage'])->middleware('checkRole:organizer')->name('myScanner');


    //Profile et guide
    Route::get('/organisateur/mon-compte', [MyProfileController::class, 'myProfile'])->middleware('checkRole:organizer')->name('myProfile');
    Route::get('/organisateur/mon-compte/formulaire-edit-profil', [MyProfileController::class, 'myProfileEdit'])->middleware('checkRole:organizer')->name('myProfiledit');
    Route::get('/organisateur/guide-utilisateur', [MyProfileController::class, 'userGuide'])->middleware('checkRole:organizer')->name('userGuide');
    Route::put('/organisateur/mon-compte/formulaire-edit-profil/update/information/{id}', [MyProfileController::class, 'updateOrganizerInfo'])->middleware('checkRole:organizer')->name('updateOrganizerInfo');
    Route::put('/organisateur/mon-compte/formulaire-edit-profil/update/password/{id}', [MyProfileController::class, 'updateOrganizerPassword'])->middleware('checkRole:organizer')->name('updateOrganizerPassword');

    //Avis
    Route::get('/organisateur/mes-évènements-avis', [MyEventNoticeController::class, 'myEventNoticeList'])->middleware('checkRole:organizer')->name('myEventNotice');
    Route::post('/organisateur/mes-évènements-avis/{id}/bloquer', [MyEventNoticeController::class, 'blockNotice'])->middleware('checkRole:organizer')->name('avis.bloquer');
    Route::post('/organisateur/mes-évènements-avis/{id}/debloquer', [MyEventNoticeController::class, 'unBlockNotice'])->middleware('checkRole:organizer')->name('avis.debloquer');

    //Commande
    Route::get('/organisateur/mes-ventes', [MyEventOderController::class, 'myEventOrderList'])->middleware('checkRole:organizer')->name('mySalle');



});
