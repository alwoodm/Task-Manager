@extends('layouts.app')

@section('content')
    <h1 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">Edit Task</h1>

    <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        
        <div>
            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title</label>
            <input type="text" name="title" id="title" value="{{ old('title', $task->title) }}" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-white rounded-md" required>
            @error('title')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description (optional)</label>
            <textarea name="description" id="description" rows="4" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-white rounded-md">{{ old('description', $task->description) }}</textarea>
            @error('description')
                <p class="mt-1 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="priority" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Priority</label>
            <select name="priority" id="priority" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-white rounded-md">
                <option value="1" {{ old('priority', $task->priority) == 1 ? 'selected' : '' }}>Low</option>
                <option value="2" {{ old('priority', $task->priority) == 2 ? 'selected' : '' }}>Medium</option>
                <option value="3" {{ old('priority', $task->priority) == 3 ? 'selected' : '' }}>High</option>
            </select>
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="is_completed" id="is_completed" value="1" {{ old('is_completed', $task->is_completed) ? 'checked' : '' }} class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-700 dark:bg-gray-800 rounded">
            <label for="is_completed" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Mark as Completed</label>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-2">
            <a href="{{ route('tasks.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                Cancel
            </a>
            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition-colors">
                <svg class="w-5 h-5 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 11.667 0l3.181-3.183m-4.991 0-3.182-3.182a8.25 8.25 0 0 0-11.667 0l-3.181 3.182M18 12.75h.008v.008H18V12.75Z" />
                </svg>
                Update Task
            </button>
        </div>
    </form>
@endsection
