@extends('base')

@section('title', 'Détails Films')

@section('content')
    

    @if (session('status'))
        <div class="alert alert-success" style="color: green;font-weight: bold;">
            {{ session('status') }}
        </div>
    @endif


    @if ($movie_data) 
        <section class="detail_movie">
            <div class="box_poster_detail">
                <div class="gradient"></div>
                <img src="{{ Storage::url('poster/'.$movie_data->id.'.jpg') }}" alt="{{ $movie_data->name }}">
            </div>

            <div class="infos">
                <div class="header_infos">
                    <h1>{{ $movie_data->name }}</h1>
                    <div class="labels_genre">
                        @foreach ($movie_data->genres as $genre)
                            <div class="label"><p>{{ $genre->name }}</p></div>
                        @endforeach
                    </div>
                </div>
                <table>
                    <tr>
                        <td>Synopsis</td>
                        <td>{{ $movie_data->overview }}</td>
                   </tr>
                   <tr>
                        <td>Réalisateur</td>
                        <td class="casting">
                            @for ($i = 0; $i < 1; $i++) 
                                <div class="actor_box">
                                    {{-- <img src="https://image.tmdb.org/t/p/w200/{{ $movie_data->credits->crew[$i]->profile_path }}" alt=""> --}}
                                    {{-- <p>{{ $movie_data->credits->crew[$i]->original_name }}</p> --}}
                                </div>
                                
                            @endfor
                        </td>
                   </tr>
                   <tr>
                        <td>Date de sortie</td>
                        {{-- <td>{{ $release_date->release_date }}</td> --}}
                   </tr>
                   <tr>
                        <td>Avis</td>
                        <td>{{ $movie_data->popularity }}</td>
                   </tr>
                   <tr>
                        <td>Casting</td>
                        <td class="casting">
                            @for ($i = 0; $i < 8; $i++) 
                                <div class="actor_box">
                                    {{-- <img src="{{ Storage::url('poster/'.$movie->id.'.jpg') }}" alt="{{ $movie->name }}">
                                    <p>{{ $movie_data->credits->cast[$i]->original_name }}</p> --}}
                                </div>
                                
                            @endfor
                        </td>
                   </tr>
            </table>
            </div>
        </section>
    @endif
    
@endsection

