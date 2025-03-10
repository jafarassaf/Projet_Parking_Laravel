<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Génère des utilisateurs de test pour l'application.
     */
    public function run(): void
    {
        // Création d'un administrateur
        User::create([
            'name' => 'Admin Système',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'is_admin' => true,
        ]);

        // Création de plusieurs utilisateurs standards
        $users = [
            [
                'name' => 'Jean Dupont',
                'email' => 'jean@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ],
            [
                'name' => 'Marie Martin',
                'email' => 'marie@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ],
            [
                'name' => 'Pierre Durand',
                'email' => 'pierre@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ],
            [
                'name' => 'Sophie Lefebvre',
                'email' => 'sophie@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ],
            [
                'name' => 'Thomas Moreau',
                'email' => 'thomas@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
            ],
        ];

        foreach ($users as $userData) {
            User::create($userData);
        }
    }
}
