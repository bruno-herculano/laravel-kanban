<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ColumnController;
use App\Http\Controllers\TaskController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return redirect()->route('boards.index');
    // return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/home', function () {
    return redirect()->route('boards.index');
    // return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [BoardController::class, 'index'])->name('home');
    Route::resource('boards', BoardController::class)->except(['show']);
    Route::get('boards/{board}', [BoardController::class, 'show'])->name('boards.show');
    Route::get('/boards/{board}/edit', [BoardController::class, 'edit'])->name('boards.edit');
    Route::put('/boards/{board}', [BoardController::class, 'update'])->name('boards.update');

    // API Routes for AJAX
    Route::post('columns', [ColumnController::class, 'store'])->name('columns.store');
    Route::put('columns/{column}', [ColumnController::class, 'update'])->name('columns.update');
    Route::delete('columns/{column}', [ColumnController::class, 'destroy'])->name('columns.destroy');
    Route::post('columns/reorder', [ColumnController::class, 'reorder'])->name('columns.reorder');

    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::post('tasks/{task}/move', [TaskController::class, 'move'])->name('tasks.move');
});

require __DIR__ . '/auth.php';

Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
