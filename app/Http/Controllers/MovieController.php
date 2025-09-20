<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use App\Models\Actor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\Paginator;

class MovieController extends Controller {

    public function home() {
        $movies = Movie::where('seen', 0)->with('genres')->get()->unique('id_movie_tmdb');
        $genres = $movies->flatMap->genres->unique('id');
        $genresWithCount = Genre::withCount('movies')->get();

        // dd($genresWithCount);

        return view('home', [
            'movies_data' => $movies,
            'genres' => $genres,
            'genresWithCount' => $genresWithCount,
        ]);
    }

    public function boot()
    {
        Paginator::useTailwind();   
    }

    public function index() {
        $movies = Movie::all();

        return view('movies.index', [
            'movies_data' => $movies,
        ]);
    }

    public function getSearch(Request $request) {
        $query = $request->input('search');
        $movies_data = $this->getCurlData('/search/movie?query='.$query.'&include_adult=false&language=fr-FR&page=1');
        return view('movies.list', [
            'movies_data' => $movies_data,
            'search_result' => $query,
            'page_title' => 'Résultats de la recherche',
        ]);
    }

    public function getType(Request $request)
    {
        $type = $request->input('type', 'movie');

        if (!in_array($type, ['movie', 'tv'], true)) {
            $type = 'movie';
        }

        $endpoint = "/{$type}/popular?language=fr-FR&page=1";
        $data = $this->getCurlData($endpoint);

        // dd($data);

        return view('movies.popular', [
            'movies_data' => $data,
            'page_title'  => $type === 'tv' ? 'Séries populaires' : 'Films populaires',
            'type'        => $type,
        ]);
    }


    public function getMovies($type) {
        $type_name = [
            'popular' => 'Films les plus populaires',
            'top_rated' => 'Films les mieux notés',
        ];
        $movies_data = $this->getCurlData('/movie/'.$type.'?language=fr-FR&page=1');
        return view('movies.list', [
            'movies_data' => $movies_data,
            'page_title' => $type_name[$type],
            'type' => $type,
        ]);
    }

    /* public function getMoviesTop() {

        $movies_data = $this->getCurlData('/movie/top_rated?language=fr-FR&page=1');
        return view('movies.top', [
            'movies_data' => $movies_data,
            'page_title' => 'All time',
        ]);
    } */

    public function getMoviesTop() {
    $all_movies = [];

    // On récupère les 5 premières pages (20 films par page)
    for ($page = 1; $page <= 5; $page++) {
        $movies_data = $this->getCurlData('/movie/top_rated?language=fr-FR&page=' . $page);

            if (isset($movies_data->results)) {
                $all_movies = array_merge($all_movies, $movies_data->results);
            }
        }

        return view('movies.top', [
            'movies_data' => $all_movies, 
            'page_title' => 'Top 100',
        ]);
    }


    public function getMoviesPopular(Request $request) {
        $page = $request->input('page', 1);
        $movies_data = $this->getCurlData('/movie/popular?language=fr-FR&page=' . $page);
        // $exists = Movie::where('id_movie_tmdb', $movie)->exists();

        return view('movies.popular', [
            'movies_data' => $movies_data,
            // 'exists' => $exists,
            'page_title' => 'Films du moment',
            'current_page' => $page, 
        ]);
    }

    public function getMoviesDetails(Request $request, Movie $movie) {
        if ($movie) {
            return view('movies.detail', [
                'movie_data' => $movie,
                'page_title' => 'Détails du film',
            ]);
        }
    }

    public function getMoviesDetailsTMDB(int $movie)
    {
        $movie_data = $this->getCurlData("/movie/{$movie}?append_to_response=credits&language=fr-FR");
        $exists = Movie::where('id_movie_tmdb', $movie)->exists();

        return view('movies.detail_tmdb', [
            'movie_data' => $movie_data,
            'exists' => $exists,
        ]);
    }


    public function storeMovie(Request $request) {

        if ($request->has('movie_id') && $request->input('movie_id') > 0) {
            $movie_data = $this->getCurlData('/movie/'.$request->input('movie_id').'?append_to_response=credits&language=fr-FR');

            // Check if the movie already exists
            $existingMovie = Movie::where('id_movie_tmdb', $movie_data->id)->first();
            if ($existingMovie) {
                return Redirect::back()->with('status', 'Ce film est déjà dans la liste');
            }

            //MOVIES (on enregistre les movies dans notre db)
            $movie = new Movie;
            $movie->id_movie_tmdb = $movie_data->id;
            $movie->name = $movie_data->title;
            $movie->image = $movie_data->poster_path;
            $movie->save();
            
            //ACTEUR (on enregitre les acteurs dans notre db)
            if(isset($movie_data->credits->cast)) {
                foreach($movie_data->credits->cast as $api_actor) {
                    $actor = new Actor;
                    $actor->name = $api_actor->name;
                    $actor->save();
                    $movie->actors()->attach($actor->id);
                }
            }

            // GENRES (on enregitre les genres dans notre db)
            if (isset($movie_data->genres)) {
                foreach ($movie_data->genres as $tmdb_genre) {
                    $genre = Genre::firstOrCreate([
                        'id_genre_tmdb' => $tmdb_genre->id,
                        'name' => $tmdb_genre->name,
                    ]);
                    $movie->genres()->attach($genre->id);
                }
            }

            // IMAGE
            if (isset($movie_data->poster_path)) {
                $path = 'poster/'.$movie->id.'.jpg';
                $response = Http::get('https://image.tmdb.org/t/p/w500/'.$movie_data->poster_path);
                Storage::disk('public')->put($path, $response->body());
            }

            return Redirect::back()->with('status', 'Film ajouté à la liste');
        }
    }

    public function setMovieSeen(Request $request) {
        if ($request->has('id_movie')) {
            $movie = Movie::find($request->input('id_movie'));
            $movie->seen = 1;
            $movie->save();
        }
        return back();
    }



    public function deleteMovie(Movie $movie) {
        $movie->delete();
        return back();
    }

    public function getCurlData($url) {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.themoviedb.org/3".$url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiIzYjZiOTA0ODUwOTAwMmI0OGFhNjE3OGFmOTg3OTdmOCIsIm5iZiI6MTUyNjg5MjY4Mi4xMTksInN1YiI6IjViMDI4ODhhMGUwYTI2MjNlMzAxM2NiNiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.U__GCj6NGxqJW_3jGpP29dEbdjeLh0eJ7a5CCmAJzlk",
                "accept: application/json"
            ],
        ]);
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if (!$err) {
            return json_decode($response);
        }
    }
    
}
