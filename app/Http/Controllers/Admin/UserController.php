<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminMiddleware;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
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
     * Affiche la liste des utilisateurs.
     */
    public function index()
    {
        $users = User::orderBy('name')->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Affiche le formulaire de création d'un nouvel utilisateur.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Enregistre un nouvel utilisateur.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'is_admin' => ['boolean'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_admin' => $request->has('is_admin'),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Affiche le formulaire d'édition d'un utilisateur.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Met à jour les informations d'un utilisateur.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $id],
            'is_admin' => ['boolean'],
        ];

        // Ajout de la validation du mot de passe seulement s'il est fourni
        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }

        $request->validate($rules);

        // Mise à jour des informations de base
        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_admin = $request->has('is_admin');

        // Mise à jour du mot de passe seulement s'il est fourni
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Informations de l\'utilisateur mises à jour avec succès.');
    }

    /**
     * Supprime un utilisateur.
     */
    public function destroy(string $id)
    {
        // Empêcher la suppression de soi-même
        if (auth()->id() == $id) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user = User::findOrFail($id);
        
        // Vérifier si l'utilisateur a des réservations actives
        if ($user->activeReservation) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de supprimer un utilisateur avec une réservation active.');
        }

        // Supprimer l'utilisateur
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
