<?php

namespace App\Http\Controllers;

use App\Models\ParkingSpace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ParkingSpaceController extends Controller
{
    /**
     * Crée une nouvelle instance de contrôleur.
     */
    public function __construct()
    {
        // Applique l'authentification à toutes les méthodes sauf index et show
        $this->middleware('auth');
    }

    /**
     * Affiche la liste des places de parking.
     */
    public function index()
    {
        $parkingSpaces = ParkingSpace::paginate(10);
        return view('parking.spaces.index', compact('parkingSpaces'));
    }

    /**
     * Affiche le formulaire de création d'une nouvelle place.
     */
    public function create()
    {
        // Vérification si l'utilisateur est admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }
        
        return view('parking.spaces.create');
    }

    /**
     * Enregistre une nouvelle place de parking.
     */
    public function store(Request $request)
    {
        // Vérification si l'utilisateur est admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }
        
        try {
            // Validation des données
            $validatedData = $request->validate([
                'space_number' => 'required|string|unique:parking_spaces,space_number',
                'description' => 'nullable|string|max:255',
            ]);
            
            // Création de la place de parking
            $parkingSpace = ParkingSpace::create([
                'space_number' => $validatedData['space_number'],
                'description' => $validatedData['description'] ?? null,
                'is_available' => true,
            ]);
            
            return redirect()->route('parking.spaces.index')
                ->with('success', 'Place de parking créée avec succès.');
                
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la création de la place de parking.')
                ->withInput();
        }
    }

    /**
     * Affiche les détails d'une place de parking spécifique.
     */
    public function show(string $id)
    {
        $parkingSpace = ParkingSpace::findOrFail($id);
        $reservations = $parkingSpace->reservations()->with('user')->latest()->paginate(10);
        
        return view('parking.spaces.show', compact('parkingSpace', 'reservations'));
    }

    /**
     * Affiche le formulaire de modification d'une place de parking.
     */
    public function edit(string $id)
    {
        // Vérification si l'utilisateur est admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }
        
        $parkingSpace = ParkingSpace::findOrFail($id);
        return view('parking.spaces.edit', compact('parkingSpace'));
    }

    /**
     * Met à jour une place de parking spécifique.
     */
    public function update(Request $request, string $id)
    {
        // Vérification si l'utilisateur est admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }
        
        try {
            $parkingSpace = ParkingSpace::findOrFail($id);
            
            // Validation des données
            $validatedData = $request->validate([
                'space_number' => 'required|string|unique:parking_spaces,space_number,' . $id,
                'description' => 'nullable|string|max:255',
                'is_available' => 'boolean',
            ]);
            
            // Mise à jour de la place de parking
            $parkingSpace->update([
                'space_number' => $validatedData['space_number'],
                'description' => $validatedData['description'] ?? null,
                'is_available' => $validatedData['is_available'] ?? $parkingSpace->is_available,
            ]);
            
            return redirect()->route('parking.spaces.index')
                ->with('success', 'Place de parking mise à jour avec succès.');
                
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la mise à jour de la place de parking.')
                ->withInput();
        }
    }

    /**
     * Supprime une place de parking spécifique.
     */
    public function destroy(string $id)
    {
        // Vérification si l'utilisateur est admin
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé.');
        }
        
        try {
            $parkingSpace = ParkingSpace::findOrFail($id);
            
            // Vérification s'il existe des réservations actives pour cette place
            if ($parkingSpace->reservations()->where('is_active', true)->exists()) {
                return back()->with('error', 'Impossible de supprimer une place de parking avec des réservations actives.');
            }
            
            $parkingSpace->delete();
            
            return redirect()->route('parking.spaces.index')
                ->with('success', 'Place de parking supprimée avec succès.');
                
        } catch (\Exception $e) {
            return back()->with('error', 'Une erreur est survenue lors de la suppression de la place de parking.');
        }
    }
}
