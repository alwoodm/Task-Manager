@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-6 pb-4 border-b">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 {{ $task->is_completed ? 'line-through text-gray-400' : '' }}">{{ $task->title }}</h2>
            <p class="text-sm text-gray-500 mt-1">
                Status: <span class="font-medium {{ $task->is_completed ? 'text-green-600' : 'text-yellow-600' }}">{{ $task->is_completed ? 'Ukończone' : 'Nieukończone' }}</span>
                 &bull; Utworzono: {{ $task->created_at->format('d.m.Y H:i') }} ({{ $task->created_at->diffForHumans() }})
                 &bull; Ostatnia aktualizacja: {{ $task->updated_at->format('d.m.Y H:i') }} ({{ $task->updated_at->diffForHumans() }})
            </p>
        </div>
        <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-4 h-4 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
              </svg>
            Powrót do listy
        </a>
    </div>

    <div class="prose max-w-none mb-6">
        @if($task->description)
            <h3 class="text-lg font-medium text-gray-700 mb-2">Opis zadania:</h3>
            <p class="{{ $task->is_completed ? 'line-through text-gray-500' : 'text-gray-700' }}">{{ $task->description }}</p>
        @else
            <p class="text-gray-500 italic">Brak opisu dla tego zadania.</p>
        @endif
    </div>

    <div class="flex flex-wrap gap-3 items-center pt-4 border-t">
        <!-- Przycisk Ukończ/Cofnij -->
        <form action="{{ route('tasks.complete', $task) }}" method="POST" class="inline">
            @csrf
            @method('PATCH')
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white {{ $task->is_completed ? 'bg-yellow-500 hover:bg-yellow-600 focus:ring-yellow-400' : 'bg-green-600 hover:bg-green-700 focus:ring-green-500' }} focus:outline-none focus:ring-2 focus:ring-offset-2">
                @if($task->is_completed)
                <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 12 12m0 0 3-3m-3 3v8M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                  </svg>
                Oznacz jako nieukończone
                @else
                <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                  </svg>
                Oznacz jako ukończone
                @endif
            </button>
        </form>

        <!-- Przycisk Edytuj -->
        <a href="{{ route('tasks.edit', $task) }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
            <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
              </svg>
            Edytuj
        </a>

        <!-- Przycisk Usuń -->
        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline" onsubmit="return confirm('Czy na pewno chcesz usunąć to zadanie: \'{{ $task->title }}\'?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                  </svg>
                Usuń
            </button>
        </form>
    </div>
@endsection
