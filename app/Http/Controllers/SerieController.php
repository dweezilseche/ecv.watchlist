<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use App\Models\Actor;
use App\Models\Serie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class SerieController extends Controller

{

    public function home() {
        // dd('test');
        $series = Serie::where('seen', 0)->with('genres')->get()->unique('id_serie_tmdb');
        $genres = $series->flatMap->genres->unique('id');
        $genresWithCount = Genre::withCount('series')->get();

        // dd($series);

        return view('home', [
            'series_data' => $series,
            'genres' => $genres,
            'genresWithCount' => $genresWithCount,
        ]);
    }


    public function getSeriesPopular(Request $request) {
        $page = $request->input('page', 1);

        $series_data = $this->getCurlData('/tv/popular?language=fr-FR&page=' . $page);

        // dd($series_data);

        return view('series.popular', [
            'series_data' => $series_data,
            'page_title' => 'Séries du moment',
            'current_page' => $page, 
        ]);
    }


    public function getSeriesTop() {
    $all_series = [];

    // On récupère les 5 premières pages (20 films par page)
    for ($page = 1; $page <= 5; $page++) {
        $series_data = $this->getCurlData('/tv/top_rated?language=fr-FR&page=' . $page);

            if (isset($series_data->results)) {
                $all_series = array_merge($all_series, $series_data->results);
            }
        }

        return view('series.top', [
            'series_data' => $all_series, 
            'page_title' => 'Top 100',
        ]);
    }

    public function getSeriesDetails(Request $request, Serie $movie) {
        if ($serie) {
            return view('series.detail', [
                'serie_data' => $serie,
                'page_title' => 'Détails de la série',
            ]);
        }
    }


    public function setSerieSeen(Request $request) {
        if ($request->has('id_serie')) {
            $serie = Serie::find($request->input('id_serie'));
            $serie->seen = 1;
            $serie->save();
        }
        return back();
    }


    public function getSeriesDetailsTMDB(int $serie)
    {
        $serie_data = $this->getCurlData("/tv/{$serie}?append_to_response=credits&language=fr-FR");
        // $exists = Movie::where('id_movie_tmdb', $serie)->exists();

        // dd($serie_data);

        return view('series.detail_tmdb', [
            'serie_data' => $serie_data,
            // 'exists' => $exists,
        ]);
    }


    public function storeSerie(Request $request) {

        if ($request->has('serie_id') && $request->input('serie_id') > 0) {
            $serie_data = $this->getCurlData('/tv/'.$request->input('serie_id').'?append_to_response=credits&language=fr-FR');

            // Check if the serie already exists
            $existingSerie = Serie::where('id_serie_tmdb', $serie_data->id)->first();
            if ($existingSerie) {
                return Redirect::back()->with('status', 'Cette série est déjà dans la liste');
            }

            //serieS (on enregistre les series dans notre db)
            $serie = new Serie;
            $serie->id_serie_tmdb = $serie_data->id;
            $serie->name = $serie_data->name;
            $serie->image = $serie_data->poster_path;
            $serie->save();
            
            //ACTEUR (on enregitre les acteurs dans notre db)
            if(isset($serie_data->credits->cast)) {
                foreach($serie_data->credits->cast as $api_actor) {
                    $actor = new Actor;
                    $actor->name = $api_actor->name;
                    $actor->save();
                    $serie->actors()->attach($actor->id);
                }
            }

            // GENRES (on enregitre les genres dans notre db)
            if (isset($serie_data->genres)) {
                foreach ($serie_data->genres as $tmdb_genre) {
                    $genre = Genre::firstOrCreate([
                        'id_genre_tmdb' => $tmdb_genre->id,
                        'name' => $tmdb_genre->name,
                    ]);
                    $serie->genres()->attach($genre->id);
                }
            }

            // IMAGE
            if (isset($serie_data->poster_path)) {
                $path = 'poster/serie/'.$serie->id.'.jpg';
                $response = Http::get('https://image.tmdb.org/t/p/w500/'.$serie_data->poster_path);
                Storage::disk('public')->put($path, $response->body());
            }

            return Redirect::back()->with('status', 'Série ajoutée à la liste');
        }
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
