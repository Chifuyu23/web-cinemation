<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MovieController::class, 'index']);
Route::get('/movies', [MovieController::class, 'movies']);
Route::get('/tv-shows', [MovieController::class, 'tvShows']);
Route::get('/search', [MovieController::class, 'search'])->name('search');
Route::get('/movie/{id}', [MovieController::class, 'movieDetails']);
Route::get('/tv/{id}', [MovieController::class, 'tvDetails']);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PanelController::class, 'index'])->name('dashboard');
    Route::get('/panel/movies', [PanelController::class, 'panel_movies'])->name('movies.index');
    Route::get('/panel/movies/form', [PanelController::class, 'movies_form'])->name('movies.form');
    Route::post('/panel/movies', [PanelController::class, 'movies_create'])->name('movies.create');
    Route::get('/panel/movies/form/{id}', [PanelController::class, 'movies_edit_form'])->name('movies.edit_form');
    Route::put('/panel/movies', [PanelController::class, 'movies_update'])->name('movies.update');
    Route::get('/panel/movies/delete/{id}', [PanelController::class, 'movies_delete'])->name('movies.delete');
    Route::get('/panel/movies/import_form', [PanelController::class, 'movies_import_form'])->name('movies.import_form');
    Route::post('/panel/movies/import', [PanelController::class, 'movies_import'])->name('movies.import');

    Route::get('/panel/tv_shows', [PanelController::class, 'panel_tv_shows'])->name('tv_shows.index');
    Route::get('/panel/tv_shows/import_form', [PanelController::class, 'tv_import_form'])->name('tv_shows.import_form');
    Route::post('/panel/tv_shows/import', [PanelController::class, 'tv_import'])->name('tv_shows.import');

    Route::get('/panel/settings', [PanelController::class, 'settings'])->name('settings');
    Route::put('/panel/settings', [PanelController::class, 'settings_update'])->name('settings.update');

    Route::get('/panel/password', [PanelController::class, 'password'])->name('password');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';