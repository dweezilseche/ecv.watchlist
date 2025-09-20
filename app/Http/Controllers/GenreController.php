<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use App\Models\Actor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class GenreController extends Controller
{
    public function getMoviesGenre(Genre $genre) {
        // dd($genresWithCount);
        $genres = Genre::all();
        $movie_genre = $genre->movies;
        $genresWithCount = Genre::withCount('movies')->get();

        return view('home', [
            'movies_data' => $movie_genre,
            'genres' => $genres,
            'genresWithCount' => $genresWithCount,
        ]);


        
    }
}
