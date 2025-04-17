<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Import Auth
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Access\AuthorizationException; // Import AuthorizationException

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Pobierz tylko zadania zalogowanego użytkownika
        $tasks = Auth::user()->tasks()->orderBy('created_at', 'desc')->get();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // Utwórz nowe zadanie powiązane z zalogowanym użytkownikiem
        Auth::user()->tasks()->create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Zadanie zostało pomyślnie dodane.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): View
    {
        // Sprawdź, czy zalogowany użytkownik jest właścicielem zadania
        if (Auth::id() !== $task->user_id) {
            abort(403); // Zwróć błąd 403 Forbidden, jeśli nie jest właścicielem
        }
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        // Sprawdź, czy zalogowany użytkownik jest właścicielem zadania
        if (Auth::id() !== $task->user_id) {
            abort(403);
        }
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        // Sprawdź, czy zalogowany użytkownik jest właścicielem zadania
        if (Auth::id() !== $task->user_id) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_completed' => 'sometimes|boolean',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'is_completed' => $request->has('is_completed') ? (bool)$request->is_completed : $task->is_completed,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Zadanie zostało pomyślnie zaktualizowane.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        // Sprawdź, czy zalogowany użytkownik jest właścicielem zadania
        if (Auth::id() !== $task->user_id) {
            abort(403);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Zadanie zostało pomyślnie usunięte.');
    }

    /**
     * Mark the specified resource as complete.
     */
    public function complete(Task $task): RedirectResponse
    {
        // Sprawdź, czy zalogowany użytkownik jest właścicielem zadania
        if (Auth::id() !== $task->user_id) {
            abort(403);
        }

        $task->update(['is_completed' => !$task->is_completed]);

        $message = $task->is_completed ? 'Zadanie oznaczone jako ukończone.' : 'Zadanie oznaczone jako nieukończone.';

        return redirect()->route('tasks.index')->with('success', $message);
    }
}