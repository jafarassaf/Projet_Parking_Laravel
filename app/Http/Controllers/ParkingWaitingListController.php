<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminMiddleware;
use App\Models\ParkingWaitingList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ParkingWaitingListController extends Controller
{
    /**
     * Crée une nouvelle instance de contrôleur.
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Appliquer le middleware admin uniquement sur certaines méthodes
        $this->middleware(AdminMiddleware::class)->except(['show', 'cancel']);
    }

    /**
     * Affiche la liste d'attente (vue administrateur).
     */
    public function index()
    {
        // Cette méthode est protégée par le middleware admin
        $waitingList = ParkingWaitingList::with('user')
            ->where('is_active', true)
            ->orderBy('position')
            ->paginate(15);
            
        return view('admin.waiting-list.index', compact('waitingList'));
    }

    /**
     * Affiche les détails d'une entrée de liste d'attente.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $waitingEntry = ParkingWaitingList::with('user')
            ->where('id', $id)
            ->where(function($query) use ($user) {
                // L'utilisateur ne peut voir que sa propre entrée, sauf s'il est admin
                if (!$user->isAdmin()) {
                    $query->where('user_id', $user->id);
                }
            })
            ->firstOrFail();
            
        return view('parking.waiting-list.show', compact('waitingEntry'));
    }

    /**
     * Met à jour l'ordre de la liste d'attente (admin uniquement).
     */
    public function updateOrder(Request $request)
    {
        // Cette méthode est protégée par le middleware admin
        try {
            $validatedData = $request->validate([
                'entries' => 'required|array',
                'entries.*.id' => 'required|exists:parking_waiting_list,id',
                'entries.*.position' => 'required|integer|min:1',
            ]);
            
            DB::beginTransaction();
            
            foreach ($validatedData['entries'] as $entry) {
                ParkingWaitingList::where('id', $entry['id'])
                    ->update(['position' => $entry['position']]);
            }
            
            DB::commit();
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    /**
     * Annule une demande dans la liste d'attente.
     */
    public function cancel(string $id)
    {
        $user = Auth::user();
        
        try {
            DB::beginTransaction();
            
            // Récupérer l'entrée de la liste d'attente
            $waitingEntry = ParkingWaitingList::where('id', $id)
                ->where(function($query) use ($user) {
                    // L'utilisateur ne peut annuler que sa propre entrée, sauf s'il est admin
                    if (!$user->isAdmin()) {
                        $query->where('user_id', $user->id);
                    }
                })
                ->where('is_active', true)
                ->firstOrFail();
            
            // Marquer l'entrée comme inactive
            $waitingEntry->update(['is_active' => false]);
            
            // Réorganiser les positions dans la liste d'attente
            ParkingWaitingList::where('position', '>', $waitingEntry->position)
                ->where('is_active', true)
                ->decrement('position');
            
            DB::commit();
            
            if ($user->isAdmin()) {
                return redirect()->route('admin.waiting-list.index')
                    ->with('success', 'Entrée de liste d\'attente supprimée avec succès.');
            } else {
                return redirect()->route('parking.reservations.index')
                    ->with('success', 'Votre demande en liste d\'attente a été annulée avec succès.');
            }
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de l\'annulation : ' . $e->getMessage());
        }
    }

    /**
     * Supprime définitivement une entrée de liste d'attente (admin uniquement).
     */
    public function destroy(string $id)
    {
        // Cette méthode est protégée par le middleware admin
        try {
            DB::beginTransaction();
            
            $waitingEntry = ParkingWaitingList::findOrFail($id);
            $position = $waitingEntry->position;
            $isActive = $waitingEntry->is_active;
            
            $waitingEntry->delete();
            
            // Si l'entrée était active, réorganiser les positions
            if ($isActive) {
                ParkingWaitingList::where('position', '>', $position)
                    ->where('is_active', true)
                    ->decrement('position');
            }
            
            DB::commit();
            
            return redirect()->route('admin.waiting-list.index')
                ->with('success', 'Entrée de liste d\'attente supprimée définitivement.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Une erreur est survenue lors de la suppression : ' . $e->getMessage());
        }
    }
}
