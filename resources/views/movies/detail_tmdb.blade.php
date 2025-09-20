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
                @if (!empty($movie_data->backdrop_path))
                    <img 
                        src="https://image.tmdb.org/t/p/w500/{{ $movie_data->backdrop_path }}" 
                        alt="{{ $movie_data->title }}">
                @endif
            </div>

            <div class="infos">
                <div class="header_infos">
                    @if (!empty($movie_data->poster_path))
                        <img 
                            width="200px" 
                            src="https://image.tmdb.org/t/p/w500/{{ $movie_data->poster_path }}" 
                            alt="{{ $movie_data->title }}" 
                            id="poster_detail">
                    @endif
                    <div class="boutons_infos">
                        <form action="{{ Route('movies.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="movie_id" value="{{ $movie_data->id }}">
                            <input type="submit" name="save_movie" value="Ajouter à ma liste" class="addlist-btn"  @if($exists) disabled style="background: #ccc; cursor: default;" value="Déjà ajouté" @endif>
                        </form>

                        {{-- <form action="{{ Route('movies.edit', ['movie' => $movie_data->id]) }}" method="GET">
                            @csrf
                            <input type="hidden" name="movie_id" value="{{ $movie_data->id }}">
                            <input type="submit" name="edit_movie" value="Modifier" id="edit-btn">
                        </form> --}}
                    </div>
                    <h1 class="title_detail">{{ $movie_data->title }}</h1>
                    <div class="labels_genre">
                        @foreach ($movie_data->genres as $genre)
                            <div class="label"><p>{{ $genre->name }}</p></div>
                        @endforeach
                    </div>
                    <div class="details_infos">
                        <div class="about">
                            <h2>Détails</h2>
                            <ul>
                                <li><strong>Réalisateur :</strong>
                                @foreach ($movie_data->credits->crew as $crew)
                                    @if ($crew->job === 'Director')
                                        {{ $crew->name }}
                                    @endif
                                @endforeach
                                </li>
                                <li><strong>Sorti le :</strong>
                                    {{ \Carbon\Carbon::parse($movie_data->release_date)->locale('fr')->isoFormat('D MMMM YYYY') }}
                                </li>
                                <li><strong>Durée :</strong> {{ $movie_data->runtime }} minutes</li>
                                <li><strong>Avis :</strong> {{ $movie_data->vote_average }} / 10 ({{ $movie_data->vote_count }} votes)</li>
                                <li><strong>Langue originale :</strong> {{ strtoupper($movie_data->original_language) }}</li>
                            </ul>
                        </div>
    
                        <div class="about">
                            <h2>Cast</h2>
                            <ul>
                                @for ($i = 0; $i < 5; $i++)
                                        <li><img src="https://image.tmdb.org/t/p/w500/{{ $movie_data->credits->cast[$i]->profile_path }}" alt=""><strong>{{ $movie_data->credits->cast[$i]->name }}</strong></li>
                                @endfor
                            </ul>
                        </div>
                    </div>

                    <div class="synopsis">
                        <h2>Storyline</h2>
                        <p>{{ $movie_data->overview }}</p>
                    </div>
                </div>
                   
            </div>
        </section>
    @endif
    
@endsection

