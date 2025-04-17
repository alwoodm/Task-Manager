@extends('layouts.app')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-10">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-300 mb-4 sm:mb-0">Task List</h2>
        <a href="{{ route('tasks.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Add New Task
        </a>
    </div>

    @if($tasks->isEmpty())
        <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 p-4 rounded-md">
            <p class="text-sm text-yellow-700 dark:text-yellow-300">No tasks to display. Add your first task!</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($tasks as $task)
                <div class="task-card border rounded-lg p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center transition-shadow duration-200 hover:shadow-md 
                    {{ $task->is_completed 
                        ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 opacity-75' 
                        : 'bg-white dark:bg-gray-800 dark:border-gray-700' }}">
                    <div class="mb-3 sm:mb-0">
                        <div class="flex items-center gap-2">
                            <h3 class="font-semibold text-lg {{ $task->is_completed ? 'line-through text-gray-500 dark:text-gray-400' : 'text-gray-800 dark:text-gray-200' }}">
                                <a href="{{ route('tasks.show', $task) }}" class="hover:text-blue-600 dark:hover:text-blue-400 hover:underline">{{ $task->title }}</a>
                            </h3>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $task->getPriorityColorClass() }}">
                                {{ $task->getPriorityLabel() }}
                            </span>
                        </div>
                        @if($task->description)
                            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1 {{ $task->is_completed ? 'line-through' : '' }}">{{ Str::limit($task->description, 120) }}</p>
                        @endif
                        <small class="text-gray-400 dark:text-gray-500 text-xs mt-1 block">Created: {{ $task->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="flex space-x-2 flex-shrink-0 mt-2 sm:mt-0">
                        <!-- Complete/Undo Button -->
                        <form action="{{ route('tasks.complete', $task) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" title="{{ $task->is_completed ? 'Mark as Incomplete' : 'Mark as Complete' }}" class="p-2 rounded-full transition-colors duration-150 {{ $task->is_completed ? 'bg-yellow-100 dark:bg-yellow-900/50 text-yellow-600 dark:text-yellow-400 hover:bg-yellow-200 dark:hover:bg-yellow-900' : 'bg-green-100 dark:bg-green-900/50 text-green-600 dark:text-green-400 hover:bg-green-200 dark:hover:bg-green-900' }}">
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

                        <!-- Edit Button -->
                        <a href="{{ route('tasks.edit', $task) }}" title="Edit Task" class="p-2 rounded-full bg-blue-100 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400 hover:bg-blue-200 dark:hover:bg-blue-900 transition-colors duration-150">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487 18.55 2.8a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>
                        </a>

                        <!-- Delete Button -->
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" title="Delete Task" class="p-2 rounded-full bg-red-100 dark:bg-red-900/50 text-red-600 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900 transition-colors duration-150">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 9.5v-2a2 2 0 0 0-2-2h-5a2 2 0 0 0-2 2v2m-3 0h14m-1 0v9a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-9m3 0v-2a4 4 0 0 1 4-4h2a4 4 0 0 1 4 4v2" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
