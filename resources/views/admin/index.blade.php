<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestion de la liste d\'attente') }}
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

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium">Liste d'attente pour les places de parking</h3>
                        <a href="{{ route('admin.dashboard') }}" class="text-blue-500 hover:underline">
                            &larr; Retour au tableau de bord
                        </a>
                    </div>

                    @if ($waitingList->isEmpty())
                        <div class="bg-blue-50 dark:bg-blue-900 p-4 rounded-lg">
                            <p class="text-blue-700 dark:text-blue-300">
                                Aucun utilisateur n'est actuellement en liste d'attente.
                            </p>
                        </div>
                    @else
                        <div class="overflow-x-auto" id="waiting-list">
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
                                            Email
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            En attente depuis
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Actions
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
                                                {{ $entry->user->email }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $entry->requested_at->format('d/m/Y H:i') }}
                                                <span class="text-xs text-gray-400 dark:text-gray-500">({{ $entry->requested_at->diffForHumans() }})</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <form action="{{ route('parking.waiting-list.cancel', $entry->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir retirer cet utilisateur de la liste d\'attente ?');">
                                                        @csrf
                                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600">
                                                            Retirer
                                                        </button>
                                                    </form>
                                                    
                                                    @if ($entry->position > 1)
                                                        <form action="{{ route('admin.waiting-list.update-order') }}" method="POST" class="ml-2">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="entries[0][id]" value="{{ $entry->id }}">
                                                            <input type="hidden" name="entries[0][position]" value="{{ $entry->position - 1 }}">
                                                            <button type="submit" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-600">
                                                                Monter
                                                            </button>
                                                        </form>
                                                    @endif
                                                    
                                                    @if ($entry->position < $waitingList->count())
                                                        <form action="{{ route('admin.waiting-list.update-order') }}" method="POST" class="ml-2">
                                                            @csrf
                                                            @method('PUT')
                                                            <input type="hidden" name="entries[0][id]" value="{{ $entry->id }}">
                                                            <input type="hidden" name="entries[0][position]" value="{{ $entry->position + 1 }}">
                                                            <button type="submit" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-600">
                                                                Descendre
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $waitingList->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 