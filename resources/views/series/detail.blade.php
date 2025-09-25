@extends('base')

@section('title', 'Détails Séries')

@section('content')


    @if (session('status'))
        <div class="alert alert-success" style="color: green;font-weight: bold;">
            {{ session('status') }}
        </div>
    @endif

    {{-- <pre style="color: #fff">{{ print_r($serie_data->toArray(), true) }}</pre> --}}

    @if ($serie_data)
        <section class="detail_movie">
            <div class="box_poster_detail">
                <div class="gradient"></div>
                @if (!empty($serie_data->backdrop))
                    <img src="{{ Storage::url('poster/serie/backdrop/' . $serie_data->id . '.jpg') }}"
                        alt="{{ $serie_data->name }}">
                @endif
            </div>

            <div class="infos">
                <div class="header_infos">
                    @if (!empty($serie_data->image))
                        <img width="200px" src="{{ Storage::url('poster/serie/cover/' . $serie_data->id . '.jpg') }}"
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
                                    @if (!empty($serie_data->director))
                                        {{ $serie_data->director }}
                                    @else
                                        Non renseigné
                                    @endif

                                </li>
                                {{-- <li><strong>Première diffusion :</strong>
                                    {{ \Carbon\Carbon::parse($serie_data->first_air_date)->locale('fr')->isoFormat('D MMMM YYYY') }}
                                </li> --}}
                                <li><strong>Nombre de saisons :</strong> {{ $serie_data->seasons }} </li>
                                <li><strong>Nombre d'épisodes :</strong> {{ $serie_data->episode_count }}</li>
                                {{-- <li><strong>Note moyenne :</strong> {{ $serie_data->vote_average }} / 10
                                    ({{ $serie_data->vote_count }} votes)</li> --}}
                            </ul>
                        </div>

                        <div class="about">
                            <h2>Cast</h2>
                            <ul>
                                @if (!empty($serie_data->actors) && count($serie_data->actors) > 0)
                                    @foreach ($serie_data->actors->take(8) as $actor)
                                        <li>
                                            <img src="{{ Storage::url('poster/actors/' . $actor->id_actor_tmdb . '.jpg') }}"
                                                alt="{{ $actor->name }}">
                                            <strong>{{ $actor->name }}</strong>
                                        </li>
                                    @endforeach

                                    @forelse ($serie_data->actors as $actor)
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

                        {{-- <pre style="color: white;">{{ print_r($episodes->toArray(), true) }}</pre> --}}

                        <form action="{{ route('series.filter.episodes', $serie_data->id) }}" method="GET">
                            <select name="season" id="season_selector" onchange="this.form.submit()">
                                <option value="">Toutes les saisons</option>
                                @for ($i = 0; $i < $serie_data->seasons; $i++)
                                    <option value="{{ $i + 1 }}"
                                        {{ request('season') == $i + 1 ? 'selected' : '' }}>
                                        Saison {{ $i + 1 }}
                                    </option>
                                @endfor
                            </select>
                        </form>

                        <div class="episode_list">
                            @if (isset($selected_season))
                                @foreach ($selected_season ? $episodes->where('season_number', $selected_season) : $episodes as $episode)
                                    <div class="episode">
                                        <form action="{{ Route('series.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="serie_id" value="{{ $serie_data->id_serie }}">
                                            <button type="submit" id="seen-btn" class="btn">
                                                <i class="fa-solid fa-check"></i>
                                            </button>
                                        </form>

                                        <h3>EP{{ $episode->episode_number }} - {{ $episode->name }}</h3>
                                        <p>{{ $episode->overview }}</p>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                </div>
        </section>
    @endif

@endsection
