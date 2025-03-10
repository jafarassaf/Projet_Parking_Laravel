<?php

namespace Database\Seeders;

use App\Models\ParkingSpace;
use Illuminate\Database\Seeder;

class ParkingSpacesTableSeeder extends Seeder
{
    /**
     * Génère des places de parking de test.
     */
    public function run(): void
    {
        // Création de places de parking numérotées
        $parkingSpaces = [];
        
        // Créer 15 places de parking
        for ($i = 1; $i <= 15; $i++) {
            $parkingSpaces[] = [
                'space_number' => 'P' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'description' => 'Place de parking n°' . $i,
                'is_available' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        // Insertion par lots pour de meilleures performances
        ParkingSpace::insert($parkingSpaces);
    }
}
