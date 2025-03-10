<?php

namespace Database\Seeders;

use App\Models\ParkingReservation;
use App\Models\ParkingSpace;
use App\Models\ParkingWaitingList;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParkingReservationsTableSeeder extends Seeder
{
    /**
     * Génère des réservations de test et une liste d'attente.
     */
    public function run(): void
    {
        // Identifier des utilisateurs non-admin pour les réservations
        $users = User::where('is_admin', false)->get();
        
        if ($users->isEmpty()) {
            $this->command->info('Aucun utilisateur disponible pour les réservations');
            return;
        }
        
        // Récupérer toutes les places de parking
        $parkingSpaces = ParkingSpace::all();
        
        if ($parkingSpaces->isEmpty()) {
            $this->command->info('Aucune place de parking disponible');
            return;
        }
        
        // Créer quelques réservations actives (maximum 5)
        $maxReservations = min(5, min($users->count(), $parkingSpaces->count()));
        
        for ($i = 0; $i < $maxReservations; $i++) {
            $user = $users[$i];
            $space = $parkingSpaces[$i];
            
            // Créer une réservation active
            ParkingReservation::create([
                'user_id' => $user->id,
                'parking_space_id' => $space->id,
                'reserved_at' => Carbon::now()->subHours(rand(1, 12)),
                'expires_at' => Carbon::now()->addDays(rand(1, 3)),
                'is_active' => true,
            ]);
            
            // Marquer la place comme non disponible
            $space->update(['is_available' => false]);
        }
        
        // Créer quelques réservations historiques
        $historyCount = min(10, $parkingSpaces->count());
        
        for ($i = 0; $i < $historyCount; $i++) {
            // Utilisateur aléatoire
            $user = $users->random();
            // Place de parking aléatoire
            $space = $parkingSpaces->random();
            
            // Date de début aléatoire dans les 30 derniers jours
            $startDate = Carbon::now()->subDays(rand(2, 30));
            // Date de fin entre le début et maintenant
            $endDate = Carbon::parse($startDate)->addHours(rand(1, 48));
            
            // Créer une réservation passée
            ParkingReservation::create([
                'user_id' => $user->id,
                'parking_space_id' => $space->id,
                'reserved_at' => $startDate,
                'expires_at' => $endDate,
                'is_active' => false,
            ]);
        }
        
        // Créer une liste d'attente
        // Utilisateurs qui n'ont pas encore de réservation active
        $usersWithActiveReservation = ParkingReservation::where('is_active', true)
            ->pluck('user_id')
            ->toArray();
        
        $availableUsers = $users->reject(function ($user) use ($usersWithActiveReservation) {
            return in_array($user->id, $usersWithActiveReservation);
        })->values(); // Réindexer le tableau pour avoir des clés séquentielles
        
        // Créer jusqu'à 3 entrées dans la liste d'attente, mais seulement si nous avons des utilisateurs disponibles
        $waitingListCount = min(3, $availableUsers->count());
        
        for ($i = 0; $i < $waitingListCount; $i++) {
            // Vérifier que l'index est valide
            if (isset($availableUsers[$i])) {
                $user = $availableUsers[$i];
                
                ParkingWaitingList::create([
                    'user_id' => $user->id,
                    'position' => $i + 1,
                    'requested_at' => Carbon::now()->subHours(rand(1, 24)),
                    'is_active' => true,
                ]);
            }
        }
    }
}
