@extends('base')

@section('title', 'Détails Films')

@section('content')


    @if (session('status'))
        <div class="alert alert-success" style="color: green;font-weight: bold;">
            {{ session('status') }}
        </div>
    @endif

    {{-- <pre style="color: #fff">{{ print_r($movie_data->toArray(), true) }}</pre> --}}

    @if ($movie_data)
        <section class="detail_movie">
            <div class="box_poster_detail">
                <div class="gradient"></div>
                @if (!empty($movie_data->backdrop))
                    <img src="{{ Storage::url('poster/movie/backdrop/' . $movie_data->id . '.jpg') }}"
                        alt="{{ $movie_data->name }}">
                @endif
            </div>

            <div class="infos">
                <div class="header_infos">
                    @if (!empty($movie_data->image))
                        <img width="200px" src="{{ Storage::url('poster/movie/cover/' . $movie_data->id . '.jpg') }}"
                            alt="{{ $movie_data->name }}" id="poster_detail">
                    @endif
                    <div class="boutons_infos">
                        {{-- <form action="{{ Route('movies.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="movie_id" value="{{ $movie_data->id }}">
                            <input type="submit" name="save_movie" value="Ajouter à ma liste" class="addlist-btn"  @if ($exists) disabled style="background: #ccc; cursor: default;" value="Déjà ajouté" @endif>
                        </form> --}}

                    </div>
                    <h1 class="title_detail">{{ $movie_data->name }}</h1>
                    <div class="labels_genre">
                        @foreach ($movie_data->genres as $genre)
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
                                    @if (!empty($movie_data->director))
                                        {{ $movie_data->director }}
                                    @else
                                        Non renseigné
                                    @endif

                                </li>
                                <li><strong>Durée :</strong> {{ $movie_data->duration }} minutes</li>
                            </ul>
                        </div>

                        <div class="about">
                            <h2>Cast</h2>
                            <ul>
                                @if (!empty($movie_data->actors) && count($movie_data->actors) > 0)
                                    @foreach ($movie_data->actors->take(5) as $actor)
                                        <li>
                                            <img src="{{ Storage::url('poster/actors/' . $actor->id_actor_tmdb . '.jpg') }}"
                                                alt="{{ $actor->name }}">
                                            <strong>{{ $actor->name }}</strong>
                                        </li>
                                    @endforeach

                                    @forelse ($movie_data->actors as $actor)
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
                        <p>{{ $movie_data->overview }}</p>
                    </div>
                </div>

            </div>
        </section>
    @endif

@endsection
