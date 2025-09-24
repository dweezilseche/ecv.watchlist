@extends('base')

@section('title', 'Accueil')

@section('content')
    {{-- <h1>Mes films</h1> --}}

    @if ($movies_data)

        <div class="box_home">
            <h2>Mes films</h2>
            <section class="box_movies">
                @foreach ($movies_data as $movie)
                    <a href="{{ Route('movies.detail', $movie->id) }}">
                        <article class="movie">
                            <form action="{{ Route('movies.seen') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_movie" value="{{ $movie->id }}">
                                <button type="submit" id="addlist-btn" class="btn">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>

                            <div class="box_poster">
                                <img src="{{ Storage::url('poster/movie/cover/' . $movie->id . '.jpg') }}"
                                    alt="{{ $movie->name }}">
                            </div>
                        </article>
                    </a>
                @endforeach
            </section>
        </div>


        <div class="box_home less_padding">
            <h2>Mes séries</h2>
            <section class="box_movies">
                @foreach ($series_data as $serie)
                    <a href="{{ Route('series.detail', $serie->id) }}">
                        <article class="serie">
                            <form action="{{ Route('series.seen') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_serie" value="{{ $serie->id }}">
                                <button type="submit" id="addlist-btn" class="btn">
                                    <i class="fa-solid fa-check"></i>
                                </button>
                            </form>

                            <div class="box_poster">
                                <img src="{{ Storage::url('poster/serie/cover/' . $serie->id . '.jpg') }}"
                                    alt="{{ $serie->name }}">
                            </div>
                        </article>
                    </a>
                @endforeach
            </section>
        </div>
    @endif

@endsection
