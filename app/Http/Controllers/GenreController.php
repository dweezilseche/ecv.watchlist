<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use App\Models\Serie;
use App\Models\Actor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class GenreController extends Controller
{
    public function getMoviesGenre(Genre $genre) {
        // dd($genre->movies);
        $movie_genre = $genre->movies;
        $serie_genre = $genre->series;

        // Movies 
        $movies = Movie::where('seen', 0)->with('genres')->get()->unique('id_movie_tmdb');
        
        // Series 
        $series = Serie::where('seen', 0)->with('genres')->get()->unique('id_serie_tmdb');
        $series_completed = Serie::where('seen', 1)->with('genres')->get()->unique('id_serie_tmdb');
        
        // dd($series);

        // Genres 
        $genres = $movies->flatMap->genres->merge($series->flatMap->genres)->unique('id');
        $genresWithCount = Genre::withCount(['movies', 'series'])->get();

        // Get current selected genre if any
        $currentGenreId = request()->get('genre');

        return view('home', [
            'movies_data' => $movie_genre,
            'series_data' => $serie_genre,
            'series_completed' => $series_completed,
            'genres' => $genres,
            'genresWithCount' => $genresWithCount,
            'currentGenreId' => $currentGenreId,
        ]);


        
    }
}
