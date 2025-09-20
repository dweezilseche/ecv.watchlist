@extends('base')

@section('title', 'Accueil')

@section('content')
    {{-- <h1>Mes films</h1> --}}

    @if ($movies_data)
        {{-- <section class="filters">
            @isset($genres)
                @foreach ($genresWithCount as $genre)
                    <div class="label">
                            <a href="{{ route('genres.genre', $genre) }}">{{ $genre->name }} ({{ $genre->movies_count }})</a>
                    </div>
                @endforeach
            @endisset
        </section> --}}


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

                        {{-- <form action="{{ Route('movies.detail') }}" method="GET">
                        @csrf
                        <input type="hidden" name="id_movie" value="{{ $movie->id }}">
                        <input type="submit" value="Plus d'infos" id="infos-btn">
                    </form> --}}
                        {{--                     
                    <div class="labels_genre_home">
                        @foreach ($movie->genres as $genre)
                            <div class="label"><p>{{ $genre->name }}</p></div>
                        @endforeach
                    </div> --}}

                        <div class="box_poster">
                            <img src="{{ Storage::url('poster/' . $movie->id . '.jpg') }}" alt="{{ $movie->name }}">
                        </div>
                        {{-- @foreach ($movie->genres as $genre)
                        <small>{{ $genre->name }}</small>
                    @endforeach --}}
                        {{-- <h2>{{ $movie->name }}</h2> --}}
                    </article>
                </a>
            @endforeach
        </section>
    @endif

@endsection
