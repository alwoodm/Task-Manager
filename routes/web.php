<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController; // Dodaj import TaskController
use App\Http\Controllers\LanguageController; // Import kontrolera języka
use Illuminate\Support\Facades\Auth; // Dodajemy import Auth
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Jeśli użytkownik jest zalogowany, przekieruj do zadań, w przeciwnym razie do strony powitalnej
    if (Auth::check()) {
        return redirect()->route('tasks.index');
    }
    return view('welcome');
});

// Trasa do zmiany języka (dostępna dla wszystkich)
Route::get('language/{locale}', [LanguageController::class, 'changeLanguage'])->name('language.switch');

// Grupa tras wymagających uwierzytelnienia
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        // Przekierowanie z dashboardu do listy zadań
        return redirect()->route('tasks.index');
    })->middleware(['verified'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Trasy dla TaskController
    Route::resource('tasks', TaskController::class)->except(['show']); // Używamy resource, show jest niestandardowy
    Route::get('tasks/{task}', [TaskController::class, 'show'])->name('tasks.show'); // Niestandardowa trasa show
    Route::patch('tasks/{task}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
});

require __DIR__.'/auth.php';
