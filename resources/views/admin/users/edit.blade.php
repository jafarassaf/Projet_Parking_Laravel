<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier l\'utilisateur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.users.index') }}" class="text-blue-500 hover:underline">
                    &larr; Retour à la liste des utilisateurs
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Modifier l'utilisateur : {{ $user->name }}</h3>
                    
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nom</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required autofocus class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @error('email')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nouveau mot de passe <span class="text-xs text-gray-500">(laisser vide pour ne pas changer)</span>
                            </label>
                            <input type="password" name="password" id="password" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmer le nouveau mot de passe</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        </div>
                        
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_admin" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }} class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Cet utilisateur est un administrateur</span>
                            </label>
                        </div>

                        <div class="mt-6">
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Statut de réservation</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-md">
                                @if ($user->activeReservation)
                                    <p class="text-sm text-green-600 dark:text-green-400">
                                        L'utilisateur a une réservation active pour la place n°{{ $user->activeReservation->parkingSpace->space_number }}
                                    </p>
                                @elseif ($user->waitingListEntry)
                                    <p class="text-sm text-yellow-600 dark:text-yellow-400">
                                        L'utilisateur est en liste d'attente à la position {{ $user->waitingListEntry->position }}
                                    </p>
                                @else
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        L'utilisateur n'a pas de réservation active et n'est pas en liste d'attente
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="{{ route('admin.users.index') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                    
                    @if (auth()->id() !== $user->id)
                        <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                            <form method="POST" action="{{ route('admin.users.destroy', $user->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                @csrf
                                @method('DELETE')
                                
                                <div class="flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Supprimer cet utilisateur
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 