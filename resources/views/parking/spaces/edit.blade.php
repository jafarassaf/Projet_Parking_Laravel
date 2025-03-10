<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier la place') }} #{{ $parkingSpace->space_number }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('parking.spaces.index') }}" class="text-blue-500 hover:underline">
                    &larr; Retour à la liste des places
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-medium mb-4">Modifier la place de parking</h3>
                    
                    <form method="POST" action="{{ route('parking.spaces.update', $parkingSpace->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label for="space_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Numéro de place</label>
                            <input type="text" name="space_number" id="space_number" value="{{ old('space_number', $parkingSpace->space_number) }}" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @error('space_number')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description (facultatif)</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $parkingSpace->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div class="mb-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_available" value="1" {{ old('is_available', $parkingSpace->is_available) ? 'checked' : '' }} class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Cette place est disponible</span>
                            </label>
                            
                            @if (!$parkingSpace->is_available)
                                <p class="mt-1 text-sm text-yellow-600 dark:text-yellow-400">
                                    Attention : Cette place est actuellement attribuée à un utilisateur. Le marquer comme disponible libérera la réservation active.
                                </p>
                            @endif
                        </div>
                        
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <a href="{{ route('parking.spaces.index') }}" class="text-sm font-semibold leading-6 text-gray-900 dark:text-gray-100">
                                Annuler
                            </a>
                            <button type="submit" class="inline-flex justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <form method="POST" action="{{ route('parking.spaces.destroy', $parkingSpace->id) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette place de parking ?');">
                            @csrf
                            @method('DELETE')
                            
                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Supprimer cette place
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 