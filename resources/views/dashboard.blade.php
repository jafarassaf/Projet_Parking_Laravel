<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tableau de bord') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Bienvenue, {{ Auth::user()->name }}</h3>
                    <p class="mb-4">Bienvenue dans l'application de gestion des places de parking. Utilisez cette plateforme pour gérer vos réservations de places de parking.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
                        <a href="{{ route('parking.spaces.index') }}" class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-800 transition duration-150">
                            <h4 class="font-semibold text-blue-700 dark:text-blue-300 mb-2">Places de parking</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Consultez toutes les places disponibles</p>
                        </a>

                        <a href="{{ route('parking.reservations.index') }}" class="bg-green-50 dark:bg-green-900 p-4 rounded-lg hover:bg-green-100 dark:hover:bg-green-800 transition duration-150">
                            <h4 class="font-semibold text-green-700 dark:text-green-300 mb-2">Mes réservations</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Gérez vos réservations de places</p>
                        </a>

                        <a href="{{ route('parking.reservations.create') }}" class="bg-purple-50 dark:bg-purple-900 p-4 rounded-lg hover:bg-purple-100 dark:hover:bg-purple-800 transition duration-150">
                            <h4 class="font-semibold text-purple-700 dark:text-purple-300 mb-2">Demander une place</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Demandez une nouvelle place de parking</p>
                        </a>
                    </div>
                </div>
            </div>

            @if (Auth::user()->isAdmin())
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Administration</h3>
                    <p class="mb-4">En tant qu'administrateur, vous avez accès à des fonctionnalités supplémentaires pour la gestion des places de parking.</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            Accéder au panneau d'administration
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
