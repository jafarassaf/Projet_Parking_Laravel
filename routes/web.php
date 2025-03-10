<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ParkingSpaceController;
use App\Http\Controllers\ParkingReservationController;
use App\Http\Controllers\ParkingWaitingListController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes pour les utilisateurs authentifiés
Route::middleware('auth')->group(function () {
    // Routes pour le profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Routes pour les places de parking (affichage uniquement pour les utilisateurs normaux)
    Route::get('parking/spaces', [ParkingSpaceController::class, 'index'])->name('parking.spaces.index');
    Route::get('parking/spaces/{id}', [ParkingSpaceController::class, 'show'])->name('parking.spaces.show');
    
    
    // Routes pour la liste d'attente (pour les utilisateurs normaux)
    Route::get('parking/waiting-list/{id}', [ParkingWaitingListController::class, 'show'])->name('parking.waiting-list.show');
    Route::post('parking/waiting-list/{id}/cancel', [ParkingWaitingListController::class, 'cancel'])->name('parking.waiting-list.cancel');

    // Routes pour l'administration (le middleware admin sera appliqué dans le constructeur du contrôleur)
    Route::prefix('admin')->group(function () {
        // Dashboard d'administration
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        
        // Gestion des places de parking (CRUD complet pour les admins)
        Route::resource('parking/spaces', ParkingSpaceController::class, [
            'except' => ['index', 'show'],
            'names' => [
                'create' => 'parking.spaces.create',
                'store' => 'parking.spaces.store',
                'edit' => 'parking.spaces.edit',
                'update' => 'parking.spaces.update',
                'destroy' => 'parking.spaces.destroy',
            ]
        ]);
        
        // Gestion de la liste d'attente (admin uniquement)
        Route::get('waiting-list', [ParkingWaitingListController::class, 'index'])->name('admin.waiting-list.index');
        Route::put('waiting-list/order', [ParkingWaitingListController::class, 'updateOrder'])->name('admin.waiting-list.update-order');
        Route::delete('waiting-list/{id}', [ParkingWaitingListController::class, 'destroy'])->name('admin.waiting-list.destroy');
        
        // Gestion des utilisateurs
        Route::resource('users', UserController::class, [
            'names' => [
                'index' => 'admin.users.index',
                'create' => 'admin.users.create',
                'store' => 'admin.users.store',
                'edit' => 'admin.users.edit',
                'update' => 'admin.users.update',
                'destroy' => 'admin.users.destroy',
            ]
        ])->except(['show']);
    });
});

require __DIR__.'/auth.php';
