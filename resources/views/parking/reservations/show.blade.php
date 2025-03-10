<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Détails de réservation') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('parking.reservations.index') }}" class="text-blue-500 hover:underline">
                    &larr; Retour à mes réservations
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Détails de la réservation</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Place de parking</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $reservation->parkingSpace->space_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date de réservation</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $reservation->reserved_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Date d'expiration</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $reservation->expires_at->format('d/m/Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Durée</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $reservation->reserved_at->diffForHumans($reservation->expires_at, true) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Statut</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                        @if ($reservation->is_active)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Terminée
                                            </span>
                                        @endif
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        
                        <div>
                            <h4 class="text-md font-medium mb-2">Informations sur la place</h4>
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Numéro</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $reservation->parkingSpace->space_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $reservation->parkingSpace->description ?? 'Aucune description' }}</dd>
                                </div>
                            </dl>
                            
                            @if ($reservation->is_active)
                                <div class="mt-6">
                                    <form action="{{ route('parking.reservations.cancel', $reservation->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?');">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Annuler la réservation
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 