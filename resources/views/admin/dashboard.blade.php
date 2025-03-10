<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Administration') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Statistiques générales -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Statistiques générales</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-blue-700 dark:text-blue-300">Places de parking</h4>
                            <div class="flex justify-between items-center mt-2">
                                <div>
                                    <p class="text-2xl font-bold text-blue-800 dark:text-blue-200">{{ $stats['total_spaces'] }}</p>
                                    <p class="text-xs text-blue-600 dark:text-blue-400">Total</p>
                                </div>
                                <div>
                                    <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $stats['available_spaces'] }}</p>
                                    <p class="text-xs text-green-500 dark:text-green-300">Disponibles</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-green-700 dark:text-green-300">Réservations</h4>
                            <p class="text-2xl font-bold text-green-800 dark:text-green-200 mt-2">{{ $stats['active_reservations'] }}</p>
                            <p class="text-xs text-green-600 dark:text-green-400">Actives</p>
                        </div>

                        <div class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-yellow-700 dark:text-yellow-300">Liste d'attente</h4>
                            <p class="text-2xl font-bold text-yellow-800 dark:text-yellow-200 mt-2">{{ $stats['waiting_list_count'] }}</p>
                            <p class="text-xs text-yellow-600 dark:text-yellow-400">Personnes en attente</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Liens rapides -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Gestion</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('parking.spaces.create') }}" class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 flex items-center">
                            <div class="bg-blue-200 dark:bg-blue-700 rounded-full p-2 mr-3">
                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-blue-700 dark:text-blue-300">Ajouter une place</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Créer une nouvelle place de parking</p>
                            </div>
                        </a>
                        
                        <a href="{{ route('admin.waiting-list.index') }}" class="bg-yellow-50 dark:bg-yellow-900 p-4 rounded-lg hover:bg-yellow-100 dark:hover:bg-yellow-800 flex items-center">
                            <div class="bg-yellow-200 dark:bg-yellow-700 rounded-full p-2 mr-3">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-yellow-700 dark:text-yellow-300">Liste d'attente</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Gérer la liste d'attente des places</p>
                            </div>
                        </a>

                        <a href="{{ route('admin.users.index') }}" class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 flex items-center">
                            <div class="bg-purple-200 dark:bg-purple-700 rounded-full p-2 mr-3">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-semibold text-purple-700 dark:text-purple-300">Utilisateurs</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Gérer les utilisateurs du système</p>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Réservations récentes -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Réservations récentes</h3>
                    
                    @if ($recentReservations->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Aucune réservation récente.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Utilisateur
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Place
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Expiration
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($recentReservations as $reservation)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $reservation->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $reservation->parkingSpace->space_number }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $reservation->reserved_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $reservation->expires_at->format('d/m/Y H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Liste d'attente -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Liste d'attente</h3>
                    
                    @if ($waitingList->isEmpty())
                        <p class="text-gray-600 dark:text-gray-400">Aucune personne en liste d'attente.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Position
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Utilisateur
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Date de demande
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Attente
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($waitingList as $entry)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $entry->position }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $entry->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $entry->requested_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $entry->requested_at->diffForHumans() }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4">
                            <a href="{{ route('admin.waiting-list.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                Voir tous &rarr;
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 