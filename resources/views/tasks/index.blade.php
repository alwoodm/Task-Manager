@extends('layouts.app')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-300 mb-4 sm:mb-0">Lista Zadań</h2>
        <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
              </svg>
            Dodaj Nowe Zadanie
        </a>
    </div>

    @if($tasks->isEmpty())
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
            <p class="text-sm text-yellow-700">Brak zadań do wyświetlenia. Dodaj swoje pierwsze zadanie!</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($tasks as $task)
                <div class="border rounded-lg p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center transition-shadow duration-200 hover:shadow-md {{ $task->is_completed ? 'bg-green-50 border-green-200 opacity-75' : 'bg-white' }}">
                    <div class="mb-3 sm:mb-0">
                        <h3 class="font-semibold text-lg {{ $task->is_completed ? 'line-through text-gray-500' : 'text-gray-800' }}">
                            <a href="{{ route('tasks.show', $task) }}" class="hover:text-blue-600 hover:underline">{{ $task->title }}</a>
                        </h3>
                        @if($task->description)
                            <p class="text-gray-600 text-sm mt-1 {{ $task->is_completed ? 'line-through' : '' }}">{{ Str::limit($task->description, 120) }}</p>
                        @endif
                        <small class="text-gray-400 text-xs mt-1 block">Utworzono: {{ $task->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="flex space-x-2 flex-shrink-0 mt-2 sm:mt-0">
                        <!-- Przycisk Ukończ/Cofnij -->
                        <form action="{{ route('tasks.complete', $task) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" title="{{ $task->is_completed ? 'Oznacz jako nieukończone' : 'Oznacz jako ukończone' }}" class="p-2 rounded-full transition-colors duration-150 {{ $task->is_completed ? 'bg-yellow-100 text-yellow-600 hover:bg-yellow-200' : 'bg-green-100 text-green-600 hover:bg-green-200' }}">
                                @if($task->is_completed)
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 12 12m0 0 3-3m-3 3v8M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                  </svg>
                                @else
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                  </svg>
                                @endif
                            </button>
                        </form>

                        <!-- Przycisk Edytuj -->
                        <a href="{{ route('tasks.edit', $task) }}" title="Edytuj zadanie" class="p-2 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors duration-150">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                              </svg>
                        </a>

                        <!-- Przycisk Usuń -->
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Usuń zadanie" class="p-2 rounded-full bg-red-100 text-red-600 hover:bg-red-200 transition-colors duration-150">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                  </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
