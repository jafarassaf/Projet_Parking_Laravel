<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Demande de place de parking') }}
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
                    <h3 class="text-lg font-medium mb-4">Demander une place de parking</h3>
                    
                    <div class="mb-6 text-gray-600 dark:text-gray-400">
                        <p>En demandant une place de parking :</p>
                        <ul class="list-disc ml-6 mt-2 space-y-1">
                            <li>Une place vous sera automatiquement attribuée si des places sont disponibles</li>
                            <li>Si aucune place n'est disponible, vous serez placé en liste d'attente</li>
                            <li>La durée par défaut d'une réservation est de 24 heures</li>
                            <li>Vous pourrez annuler votre réservation à tout moment</li>
                        </ul>
                    </div>
                    
                    <form method="POST" action="{{ route('parking.reservations.store') }}">
                        @csrf
                        
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="{{ route('parking.reservations.index') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex justify-center rounded-md bg-blue-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                                Demander une place
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 