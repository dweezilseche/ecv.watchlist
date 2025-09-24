@extends('base')

@section('title', 'Détails Films')

@section('content')


    @if (session('status'))
        <div class="alert alert-success" style="color: green;font-weight: bold;">
            {{ session('status') }}
        </div>
    @endif


    @if ($serie_data)
        <section class="detail_movie">
            <div class="box_poster_detail">
                <div class="gradient"></div>
                @if (!empty($serie_data->backdrop_path))
                    <img src="https://image.tmdb.org/t/p/w500/{{ $serie_data->backdrop_path }}" alt="{{ $serie_data->name }}">
                @endif
            </div>

            <div class="infos">
                <div class="header_infos">
                    @if (!empty($serie_data->poster_path))
                        <img width="200px" src="https://image.tmdb.org/t/p/w500/{{ $serie_data->poster_path }}"
                            alt="{{ $serie_data->name }}" id="poster_detail">
                    @endif
                    <div class="boutons_infos">
                        {{-- <form action="{{ Route('movies.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="movie_id" value="{{ $serie_data->id }}">
                            <input type="submit" name="save_movie" value="Ajouter à ma liste" class="addlist-btn"  @if ($exists) disabled style="background: #ccc; cursor: default;" value="Déjà ajouté" @endif>
                        </form> --}}

                    </div>
                    <h1 class="title_detail">{{ $serie_data->name }}</h1>
                    <div class="labels_genre">
                        @foreach ($serie_data->genres as $genre)
                            <div class="label">
                                <p>{{ $genre->name }}</p>
                            </div>
                        @endforeach
                    </div>
                    <div class="details_infos">
                        <div class="about">
                            <h2>Détails</h2>
                            <ul>
                                <li><strong>Réalisateur :</strong>
                                    @foreach ($serie_data->credits->crew as $crew)
                                        @if ($crew->job === 'Director')
                                            {{ $crew->name }}
                                        @endif
                                    @endforeach
                                </li>
                                <li><strong>Première diffusion :</strong>
                                    {{ \Carbon\Carbon::parse($serie_data->first_air_date)->locale('fr')->isoFormat('D MMMM YYYY') }}
                                </li>
                                <li><strong>Nombre de saisons :</strong> {{ $serie_data->number_of_seasons }} </li>
                                <li><strong>Nombre d'épisodes :</strong> {{ $serie_data->number_of_episodes }}</li>
                                <li><strong>Note moyenne :</strong> {{ $serie_data->vote_average }} / 10
                                    ({{ $serie_data->vote_count }} votes)</li>
                            </ul>
                        </div>

                        <div class="about">
                            <h2>Cast</h2>
                            <ul>
                                @if (!empty($serie_data->credits->cast) && count($serie_data->credits->cast) > 0)
                                    @for ($i = 0; $i < min(8, count($serie_data->credits->cast)); $i++)
                                        <li><img src="https://image.tmdb.org/t/p/w500/{{ $serie_data->credits->cast[$i]->profile_path }}"
                                                alt=""><strong>{{ $serie_data->credits->cast[$i]->name }}</strong>
                                        </li>
                                    @endfor

                                    @forelse ($serie_data->credits->cast as $actor)
                                    @empty
                                        <li>Non renseigné</li>
                                    @endforelse
                                @else
                                    <li>Non renseigné</li>
                                @endif

                            </ul>
                        </div>
                    </div>

                    <div class="synopsis">
                        <h2>Storyline</h2>
                        <p>{{ $serie_data->overview }}</p>
                    </div>

                    <div class="all_episodes">
                        <h2>Tous les épisodes</h2>
                        <select name="seasons" id="season_selector">
                            <option value="season_1">Saison 1</option>
                        </select>

                        <div class="episode">
                            <h3>Episode XX - Titre de l'épisode</h3>
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nemo qui sapiente aut
                                exercitationem, delectus blanditiis itaque vero, modi fugiat tempore libero consequuntur
                                soluta quia, laboriosam maiores saepe quam obcaecati. Dicta?</p>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    @endif

@endsection
