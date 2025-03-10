<?php

namespace App\Http\Controllers;

use App\Models\ParkingReservation;
use App\Models\ParkingSpace;
use App\Models\ParkingWaitingList;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParkingReservationController extends Controller
{
    /**
     * Crée une nouvelle instance de contrôleur.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Affiche les réservations de l'utilisateur connecté.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer la réservation active de l'utilisateur
        $activeReservation = $user->activeReservation;
        
        // Récupérer l'historique des réservations de l'utilisateur
        $reservationHistory = ParkingReservation::where('user_id', $user->id)
            ->where('is_active', false)
            ->with('parkingSpace')
            ->latest('reserved_at')
            ->paginate(10);
            
        // Vérifier si l'utilisateur est en liste d'attente
        $waitingListEntry = $user->waitingListEntry;
        
        return view('parking.reservations.index', compact(
            'activeReservation', 
            'reservationHistory', 
            'waitingListEntry'
        ));
    }

    /**
     * Affiche le formulaire de demande de réservation.
     */
    public function create()
    {
        $user = Auth::user();
        
        // Vérifier si l'utilisateur a déjà une réservation active
        if ($user->activeReservation) {
            return redirect()->route('parking.reservations.index')
                ->with('error', 'Vous avez déjà une réservation active.');
        }
        
        // Vérifier si l'utilisateur est déjà en liste d'attente
        if ($user->waitingListEntry) {
            return redirect()->route('parking.reservations.index')
                ->with('error', 'Vous êtes déjà inscrit sur la liste d\'attente.');
        }
        
        return view('parking.reservations.create');
    }

    /**
     * Traite la demande de réservation de place de parking.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Vérifier si l'utilisateur a déjà une réservation active
        if ($user->activeReservation) {
            return redirect()->route('parking.reservations.index')
                ->with('error', 'Vous avez déjà une réservation active.');
        }
        
        // Vérifier si l'utilisateur est déjà en liste d'attente
        if ($user->waitingListEntry) {
            return redirect()->route('parking.reservations.index')
                ->with('error', 'Vous êtes déjà inscrit sur la liste d\'attente.');
        }
        
        try {
            DB::beginTransaction();
            
            // Rechercher une place de parking disponible
            $availableSpace = ParkingSpace::where('is_available', true)
                ->inRandomOrder()
                ->first();
            
            if ($availableSpace) {
                // Calcul de la date d'expiration (par défaut 1 jour)
                $reservedAt = Carbon::now();
                $expiresAt = $reservedAt->copy()->addDay();
                
                // Créer la réservation
                $reservation = ParkingReservation::create([
                    'user_id' => $user->id,
                    'parking_space_id' => $availableSpace->id,
                    'reserved_at' => $reservedAt,
                    'expires_at' => $expiresAt,
                    'is_active' => true,
                ]);
                
                // Mettre à jour le statut de la place de parking
                $availableSpace->update(['is_available' => false]);
                
                DB::commit();
                
                return redirect()->route('parking.reservations.index')
                    ->with('success', 'Place de parking n°' . $availableSpace->space_number . ' attribuée avec succès.');
            } else {
                // Aucune place disponible, inscrire l'utilisateur en liste d'attente
                
                // Déterminer la position dans la liste d'attente
                $lastPosition = ParkingWaitingList::where('is_active', true)
                    ->max('position') ?? 0;
                
                // Créer l'entrée de liste d'attente
                ParkingWaitingList::create([
                    'user_id' => $user->id,
                    'position' => $lastPosition + 1,
                    'requested_at' => Carbon::now(),
                    'is_active' => true,
                ]);
                
                DB::commit();
                
                return redirect()->route('parking.reservations.index')
                    ->with('info', 'Toutes les places sont occupées. Vous êtes inscrit en liste d\'attente à la position ' . ($lastPosition + 1) . '.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la réservation : ' . $e->getMessage());
        }
    }

    /**
     * Affiche les détails d'une réservation.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $reservation = ParkingReservation::with('parkingSpace')
            ->where('id', $id)
            ->where(function($query) use ($user) {
                // L'utilisateur ne peut voir que ses propres réservations, sauf s'il est admin
                if (!$user->isAdmin()) {
                    $query->where('user_id', $user->id);
                }
            })
            ->firstOrFail();
            
        return view('parking.reservations.show', compact('reservation'));
    }

    /**
     * Annule une réservation active.
     */
    public function cancel(string $id)
    {
        $user = Auth::user();
        
        try {
            DB::beginTransaction();
            
            // Récupérer la réservation
            $reservation = ParkingReservation::where('id', $id)
                ->where(function($query) use ($user) {
                    // L'utilisateur ne peut annuler que ses propres réservations, sauf s'il est admin
                    if (!$user->isAdmin()) {
                        $query->where('user_id', $user->id);
                    }
                })
                ->where('is_active', true)
                ->firstOrFail();
            
            // Mettre à jour la réservation
            $reservation->update([
                'is_active' => false,
                'expires_at' => Carbon::now(),
            ]);
            
            // Libérer la place de parking
            $parkingSpace = $reservation->parkingSpace;
            $parkingSpace->update(['is_available' => true]);
            
            // Vérifier s'il y a des personnes en liste d'attente
            $waitingUser = ParkingWaitingList::where('is_active', true)
                ->orderBy('position')
                ->first();
                
            if ($waitingUser) {
                // Attribuer la place au premier utilisateur en liste d'attente
                
                // Créer la nouvelle réservation
                $newReservation = ParkingReservation::create([
                    'user_id' => $waitingUser->user_id,
                    'parking_space_id' => $parkingSpace->id,
                    'reserved_at' => Carbon::now(),
                    'expires_at' => Carbon::now()->addDay(),
                    'is_active' => true,
                ]);
                
                // Marquer la place comme indisponible à nouveau
                $parkingSpace->update(['is_available' => false]);
                
                // Supprimer l'utilisateur de la liste d'attente
                $waitingUser->update(['is_active' => false]);
                
                // Réorganiser les positions dans la liste d'attente
                ParkingWaitingList::where('position', '>', $waitingUser->position)
                    ->where('is_active', true)
                    ->decrement('position');
            }
            
            DB::commit();
            
            return redirect()->route('parking.reservations.index')
                ->with('success', 'Réservation annulée avec succès.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de l\'annulation de la réservation : ' . $e->getMessage());
        }
    }
}
