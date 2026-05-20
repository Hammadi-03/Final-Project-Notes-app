<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

// Landing page
Route::get('/', function () {
    return view('landing');
})->name('home');

// Auth routes (guests only)
Route::middleware('guest')->group(function () {
    Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',   [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register',[AuthController::class, 'register']);
});

// Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Notes CRUD (authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/notes',            [NoteController::class, 'index'])->name('notes.index');
    Route::get('/notes/create',     [NoteController::class, 'create'])->name('notes.create');
    Route::post('/notes',           [NoteController::class, 'store'])->name('notes.store');
    Route::delete('/notes/empty-trash',   [NoteController::class, 'emptyTrash'])->name('notes.emptyTrash');
    Route::get('/notes/{note}/edit',[NoteController::class, 'edit'])->name('notes.edit');
    Route::put('/notes/{note}',     [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}',  [NoteController::class, 'destroy'])->name('notes.destroy');
    Route::post('/notes/{note}/pin',[NoteController::class, 'togglePin'])->name('notes.pin');
    Route::patch('/notes/{note}/color',[NoteController::class, 'updateColor'])->name('notes.color');
    Route::post('/notes/{note}/image',[NoteController::class, 'uploadImage'])->name('notes.image');
    Route::post('/notes/{note}/copy',     [NoteController::class, 'duplicate'])->name('notes.duplicate');
    
    // Trash routes
    Route::get('/trash',                  [NoteController::class, 'trash'])->name('notes.trash');
    Route::post('/notes/{id}/restore',    [NoteController::class, 'restore'])->name('notes.restore');
    Route::delete('/notes/{id}/force',    [NoteController::class, 'forceDelete'])->name('notes.forceDelete');

    // Archive routes
    Route::get('/archive',                [NoteController::class, 'archive'])->name('notes.archive');
    Route::post('/notes/{note}/archive',  [NoteController::class, 'toggleArchive'])->name('notes.toggleArchive');

    // Label routes
    Route::post('/labels',                [NoteController::class, 'storeLabel'])->name('labels.store');
    Route::put('/labels/{label}',         [NoteController::class, 'updateLabel'])->name('labels.update');
    Route::delete('/labels/{label}',      [NoteController::class, 'destroyLabel'])->name('labels.destroy');
    Route::get('/labels/{label}',         [NoteController::class, 'labelView'])->name('labels.show');
    Route::post('/notes/{note}/labels',   [NoteController::class, 'toggleNoteLabel'])->name('notes.toggleLabel');

    // User settings & Feedback routes
    Route::post('/settings',              [NoteController::class, 'saveSettings'])->name('settings.save');
    Route::post('/feedback',              [NoteController::class, 'submitFeedback'])->name('feedback.submit');
});
