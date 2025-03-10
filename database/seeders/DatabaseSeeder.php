<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appel des seeders personnalisés dans l'ordre approprié
        $this->call([
            // D'abord les utilisateurs
            UsersTableSeeder::class,
            // Ensuite les places de parking
            ParkingSpacesTableSeeder::class,
            // Enfin les réservations et la liste d'attente
            ParkingReservationsTableSeeder::class,
        ]);
    }
}
