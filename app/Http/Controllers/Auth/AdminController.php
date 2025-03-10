<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminMiddleware;
use App\Models\ParkingSpace;
use App\Models\ParkingReservation;
use App\Models\ParkingWaitingList;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Crée une nouvelle instance de contrôleur.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(AdminMiddleware::class);
    }

    /**
     * Affiche le tableau de bord d'administration.
     */
    public function index()
    {
        // Récupérer des statistiques pour le tableau de bord
        $stats = [
            'total_spaces' => ParkingSpace::count(),
            'available_spaces' => ParkingSpace::where('is_available', true)->count(),
            'active_reservations' => ParkingReservation::where('is_active', true)->count(),
            'waiting_list_count' => ParkingWaitingList::where('is_active', true)->count(),
            'total_users' => User::count(),
            'admin_users' => User::where('is_admin', true)->count(),
        ];
        
        // Récupérer les données pour les tableaux du tableau de bord
        $recentReservations = ParkingReservation::with(['user', 'parkingSpace'])
            ->latest()
            ->take(5)
            ->get();
            
        $waitingList = ParkingWaitingList::with('user')
            ->where('is_active', true)
            ->orderBy('position')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact('stats', 'recentReservations', 'waitingList'));
    }
}
