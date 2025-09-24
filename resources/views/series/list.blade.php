@extends('base')

@section('title', 'Résultats de la recherche')

@section('content')
    <h1>{{ $page_title }}</h1>
    <p class="search_result">"{{ $search_result }}"</p>



    @if (session('status'))
        <div class="alert alert-success" style="color: green;font-weight: bold;">
            {{ session('status') }}
        </div>
    @endif


    @if ($movies_data->results)
        <section class="box_movies">
            @foreach ($movies_data->results as $movie)
                <article>
                    {{-- <h2>{{ $movie->title }}</h2> --}}
                    <div class="box_poster">
                        <img width="100%" src="https://image.tmdb.org/t/p/w500/{{ $movie->poster_path }}"
                            alt="{{ $movie->title }}">
                    </div>
                    {{-- <small>{{ $movie->id }}</small> --}}

                    <form action="{{ Route('movies.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                        <input type="submit" name="save_movie" value="À voir" id="addlist-btn">
                    </form>

                    <form action="{{ Route('movies.detail_tmdb', [$movie->id]) }}" method="GET">
                        @csrf
                        <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                        <input type="submit" name="see_details" value="Voir plus" id="detail-btn">
                    </form>


                </article>
            @endforeach
        </section>
    @endif

    @if (!$movies_data->results)
        <p class="no_exist">Ce film n'existe pas...</p>
    @else
    @endif

@endsection
