<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\TypeChambreController;
use App\Http\Controllers\Admin\TypeReservationController;
use App\Http\Controllers\Admin\ChambreController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\PaiementController as AdminPaiementController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TarifController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Client\AccueilController;
use App\Http\Controllers\Client\ContactController as ClientContactController;
use App\Http\Controllers\Client\PaiementController as ClientPaiementController;
use App\Http\Controllers\Client\RoomController;
use App\Http\Controllers\Client\ReservationController;

/*
|--------------------------------------------------------------------------
| Routes Publiques
|--------------------------------------------------------------------------
*/
Route::get('/', [AccueilController::class, 'index'])->name('client.accueil');
Route::get('/about', fn() => view('client.about'))->name('client.about');
Route::get('/gallery', [RoomController::class, 'index'])->name('client.gallery');
Route::get('/room-details/{id}', [RoomController::class, 'show'])->name('client.room.room-details');
Route::get('/contact', [ClientContactController::class, 'index'])->name('client.contact');
Route::post('/contact', [ClientContactController::class, 'store'])->name('client.contact.store');


// create + store publics — auth gérée manuellement dans le contrôleur
Route::get('/reservation/create/{id}', [ReservationController::class, 'create'])->name('client.reservation.create');
Route::post('/reservation', [ReservationController::class, 'store'])->name('client.reservation.store'); // ✅ UNE SEULE FOIS ici

// AJAX calendrier (public)
Route::get('/reservation/dates-reservees', [ReservationController::class, 'datesReservees'])->name('client.reservation.dates-reservees');

/*
|--------------------------------------------------------------------------
| Routes Guest
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
    Route::get('/forgot-password', [PasswordLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

/*
|--------------------------------------------------------------------------
| Routes Authentifiées (Client)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/change-password', [PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/change-password', [PasswordController::class, 'update'])->name('password.update');

    // Réservations — nécessitent auth
    Route::get('/reservation', [ReservationController::class, 'index'])->name('client.reservation.index');
    Route::get('/reservation/{id}', [ReservationController::class, 'show'])->name('client.reservation.show');
    Route::delete('/reservation/{id}/annuler', [ReservationController::class, 'annuler'])->name('client.reservation.annuler');

    // Paiements
    Route::get('/paiement/{id}', [ClientPaiementController::class, 'show'])->name('client.paiement.show');
    Route::post('/paiement/{id}', [ClientPaiementController::class, 'store'])->name('client.paiement.store');
    Route::get('/paiement-success', [ClientPaiementController::class, 'success'])->name('client.paiement.success');

    //recu paiement
    Route::get('/paiement/recu/{id}',     [ClientPaiementController::class, 'recu'])->name('client.paiement.recu');
    Route::get('/paiement/recu/{id}/pdf', [ClientPaiementController::class, 'recuPdf'])->name('client.paiement.recu.pdf');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::get('/admin', function () {
    if (Auth::check() && Auth::user()->isAdmin()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('login');
})->name('admin.home');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    // Tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestion des types de chambres
    Route::resource('type_chambres', TypeChambreController::class)->except(['show']);

    // Gestion des types de réservation
    Route::resource('types_reservation', TypeReservationController::class);

    // Gestion des tarifs
    Route::resource('tarifs', TarifController::class)->except(['show']);

    // Gestion des chambres
    Route::resource('chambres', ChambreController::class)->except(['show']);

    // Gestion des réservations (admin)
    Route::prefix('reservations')->name('reservations.')->group(function () {
        Route::get('/', [AdminReservationController::class, 'index'])->name('index');
        Route::get('/create', [AdminReservationController::class, 'create'])->name('create');
        Route::post('/', [AdminReservationController::class, 'store'])->name('store');

        // Route utilitaire AJAX
        Route::get('/chambres-disponibles', [AdminReservationController::class, 'chambresDisponibles'])->name('chambres-disponibles');
        Route::get('/dates-reservees', [AdminReservationController::class, 'datesReservees'])->name('dates-reservees');
        
        //Reservation
        Route::get('/{reservation}', [AdminReservationController::class, 'show'])->name('show');
        Route::get('/{reservation}/edit', [AdminReservationController::class, 'edit'])->name('edit');
        Route::patch('/{reservation}', [AdminReservationController::class, 'update'])->name('update');
        Route::delete('/{reservation}', [AdminReservationController::class, 'destroy'])->name('destroy');
        
        // Actions spécifiques sur les réservations
        Route::post('/{reservation}/confirmer', [AdminReservationController::class, 'confirmer'])->name('confirmer');
        Route::post('/{reservation}/annuler', [AdminReservationController::class, 'annuler'])->name('annuler');
        Route::post('/{reservation}/terminer', [AdminReservationController::class, 'terminer'])->name('terminer');
        //Recu paiement
    Route::get('/{reservation}/recu-pdf', [AdminReservationController::class, 'recuPdf'])->name('recu-pdf');
    });

    // Gestion des paiements (admin)
    Route::get('/paiements', [AdminPaiementController::class, 'index'])->name('paiements.index');
    Route::get('/paiements/{id}/show',[AdminPaiementController::class, 'show'])->name('paiements.show');

    // Gestion des rôles
    Route::resource('roles', RoleController::class)->except(['show']);
    Route::patch('/roles/{role}/toggle', [RoleController::class, 'toggleStatus'])->name('roles.toggle');

    // Gestion des contacts (messages)
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', [AdminContactController::class, 'index'])->name('index');
        Route::get('/{contact}', [AdminContactController::class, 'show'])->name('show');
        Route::delete('/{contact}', [AdminContactController::class, 'destroy'])->name('destroy');
    });

    // Gestion des utilisateurs
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('toggle-status');
        Route::get('/liste-admins', [UserController::class, 'admins'])->name('admins');
    });
});