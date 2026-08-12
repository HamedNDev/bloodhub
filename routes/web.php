<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DemandeController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\PaiementController;
use Illuminate\Support\Facades\Route;

// Pages publiques
Route::get('/', [\App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/disponibilites', [StockController::class, 'public'])->name('stocks.public');

// Cagnottes publiques (sans connexion)
Route::get('/cagnottes', [\App\Http\Controllers\CagnotteController::class, 'publique'])->name('cagnottes.publique');
Route::get('/cagnottes/{slug}', [\App\Http\Controllers\CagnotteController::class, 'voir'])->name('cagnottes.voir');
Route::post('/cagnottes/{slug}/contribuer', [\App\Http\Controllers\CagnotteController::class, 'contribuer'])->name('cagnottes.contribuer');

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil
    Route::get('/profil', [\App\Http\Controllers\ProfilController::class, 'show'])->name('profil.show');
    Route::get('/profil/edit', [\App\Http\Controllers\ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [\App\Http\Controllers\ProfilController::class, 'update'])->name('profil.update');
    Route::patch('/profil/password', [\App\Http\Controllers\ProfilController::class, 'updatePassword'])->name('profil.password');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}', [\App\Http\Controllers\NotificationController::class, 'marquerLue'])->name('notifications.lue');
    Route::patch('/notifications', [\App\Http\Controllers\NotificationController::class, 'marquerToutesLues'])->name('notifications.toutes-lues');
    Route::delete('/notifications/{notification}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Donneur
    Route::middleware('role:donneur')->prefix('donneur')->name('donneur.')->group(function () {
        Route::patch('/disponibilite', [\App\Http\Controllers\DonneurController::class, 'toggleDisponibilite'])->name('toggle');
        Route::get('/alertes', [\App\Http\Controllers\DonneurController::class, 'alertes'])->name('alertes');
        Route::patch('/alertes/{notification}', [\App\Http\Controllers\DonneurController::class, 'marquerAlerteLue'])->name('alerte.lue');
        Route::get('/historique', [\App\Http\Controllers\DonneurController::class, 'historique'])->name('historique');
        Route::post('/don', [\App\Http\Controllers\DonneurController::class, 'enregistrerDon'])->name('enregistrer-don');
    });

    // Demandes
    Route::get('/demandes', [DemandeController::class, 'index'])->name('demandes.index');
    Route::get('/demandes/create', [DemandeController::class, 'create'])->name('demandes.create');
    Route::post('/demandes', [DemandeController::class, 'store'])->name('demandes.store');
    Route::get('/demandes/{demande}', [DemandeController::class, 'show'])->name('demandes.show');
    Route::patch('/demandes/{demande}/statut', [DemandeController::class, 'updateStatut'])->name('demandes.statut')->middleware('role:admin,hopital,banque_de_sang');

    // Stocks (banque de sang + admin)
    Route::middleware('role:banque_de_sang,admin')->group(function () {
        Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
        Route::patch('/stocks/{groupe}', [StockController::class, 'update'])->name('stocks.update');
    });

    // Paiements
    Route::get('/paiements', [PaiementController::class, 'index'])->name('paiements.index');
    Route::get('/paiements/{demande}/create', [PaiementController::class, 'create'])->name('paiements.create');
    Route::post('/paiements/{demande}', [PaiementController::class, 'store'])->name('paiements.store');
    Route::get('/paiements/{paiement}/recu', [PaiementController::class, 'show'])->name('paiements.show');
    Route::get('/paiements/{paiement}/verifier', [PaiementController::class, 'verifierStatut'])->name('paiements.verifier');

    // Bons de paiement
    Route::get('/bons', [\App\Http\Controllers\BonPaiementController::class, 'index'])->name('bons.index');
    Route::get('/bons/create', [\App\Http\Controllers\BonPaiementController::class, 'create'])->name('bons.create');
    Route::post('/bons', [\App\Http\Controllers\BonPaiementController::class, 'store'])->name('bons.store');
    Route::get('/bons/{bon}', [\App\Http\Controllers\BonPaiementController::class, 'show'])->name('bons.show');
    Route::post('/bons/utiliser', [\App\Http\Controllers\BonPaiementController::class, 'utiliser'])->name('bons.utiliser');

    // Cagnottes (authentifié)
    Route::get('/mes-cagnottes', [\App\Http\Controllers\CagnotteController::class, 'index'])->name('cagnottes.index');
    Route::get('/mes-cagnottes/create', [\App\Http\Controllers\CagnotteController::class, 'create'])->name('cagnottes.create');
    Route::post('/mes-cagnottes', [\App\Http\Controllers\CagnotteController::class, 'store'])->name('cagnottes.store');
    Route::delete('/mes-cagnottes/{cagnotte}', [\App\Http\Controllers\CagnotteController::class, 'destroy'])->name('cagnottes.destroy');

});

// Callbacks CamPay — hors auth car CamPay redirige sans session
Route::get('/paiements/callback', [PaiementController::class, 'callback'])->name('paiements.callback');
Route::get('/paiements/echec', [PaiementController::class, 'echec'])->name('paiements.echec');

Route::middleware('auth')->group(function () {
    // Admin
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['create', 'store']);
        Route::post('users/{user}/promouvoir', [\App\Http\Controllers\Admin\UserController::class, 'promouvoir'])->name('users.promouvoir');
        Route::resource('hopitaux', \App\Http\Controllers\Admin\HopitalController::class);
        Route::resource('banques', \App\Http\Controllers\Admin\BanqueController::class);
        Route::get('/statistiques', [\App\Http\Controllers\StatistiqueController::class, 'index'])->name('statistiques');

        // Donneurs
        Route::get('/donneurs', [\App\Http\Controllers\Admin\DonneurAdminController::class, 'index'])->name('donneurs.index');
        Route::patch('/donneurs/{user}/toggle-actif', [\App\Http\Controllers\Admin\DonneurAdminController::class, 'toggleActif'])->name('donneurs.toggle-actif');
        Route::patch('/donneurs/{user}/toggle-disponible', [\App\Http\Controllers\Admin\DonneurAdminController::class, 'toggleDisponible'])->name('donneurs.toggle-disponible');

        // Paiements admin
        Route::get('/paiements', [\App\Http\Controllers\Admin\PaiementAdminController::class, 'index'])->name('paiements.index');

        // Notifications admin
        Route::get('/notifications', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'index'])->name('notifications.index');
        Route::post('/notifications/envoyer', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'envoyer'])->name('notifications.envoyer');
        Route::delete('/notifications/{notification}', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'destroy'])->name('notifications.destroy');
        Route::delete('/notifications', [\App\Http\Controllers\Admin\AdminNotificationController::class, 'destroyAll'])->name('notifications.destroy-all');
    });
});

