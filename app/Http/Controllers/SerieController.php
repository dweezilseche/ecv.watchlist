<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use App\Models\Actor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class SerieController extends Controller
{
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
