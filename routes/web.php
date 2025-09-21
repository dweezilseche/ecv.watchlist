<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\SerieController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MovieController::class, 'home'])->name('home');

// Route::get('/', [SerieController::class, 'home'])->name('home');

Route::controller(MovieController::class)->prefix('/movies')->name('movies.')->group(function () {
    // FRONT
    Route::get('/populaire', 'getMoviesPopular')->name('popular');
    Route::get('/top', 'getMoviesTop')->name('top');
    Route::get('/detail/{movie}', 'getMoviesDetails')->name('detail');
    Route::get('/detail_tmdb/{movie}', 'getMoviesDetailsTMDB')->name('detail_tmdb');
    Route::get('/type', 'getType')->name('type');
    Route::get('/search', 'getSearch')->name('search');
    Route::post('/store', 'storeMovie')->name('store');
    Route::post('/seen', 'setMovieSeen')->name('seen');
    Route::delete('/delete/{movie}', 'deleteMovie')->name('delete');
    // GESTION
    Route::get('/index', 'index')->name('index');

});

Route::controller(SerieController::class)->prefix('/series')->name('series.')->group(function () {
    // FRONT
    Route::get('/populaire', 'getSeriesPopular')->name('popular');
    Route::get('/top', 'getSeriesTop')->name('top');
    Route::get('/detail_tmdb/{serie}', 'getSeriesDetailsTMDB')->name('detail_tmdb');
    Route::get('/detail/{serie}', 'getSeriesDetails')->name('detail');
    Route::post('/seen', 'setSerieSeen')->name('seen');
    Route::post('/store', 'storeSerie')->name('store');

});

Route::controller(GenreController::class)->prefix('/genres')->name('genres.')->group(function () {
    Route::get('/{genre}', 'getMoviesGenre')->name('genre');
});

