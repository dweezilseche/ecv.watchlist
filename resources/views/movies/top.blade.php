@extends('base')

@section('title', 'Films les mieux notés')

@section('content')
    {{-- <h1>{{ $page_title }}</h1> --}}



    @if (session('status'))
        <div class="alert alert-success" style="color: green;font-weight: bold;">
            {{ session('status') }}
        </div>
    @endif


    @if ($movies_data)
        <section class="box_movies">
            @foreach ($movies_data as $movie)
                <a href="{{ Route('movies.detail_tmdb', ['movie' => $movie->id]) }}">
                    <article>
                        {{-- <h2>{{ $movie->title }}</h2> --}}
                        <div class="box_poster">
                            <div class="ranking">
                                {{ $loop->iteration }}
                            </div>
                            <img width="100%" src="https://image.tmdb.org/t/p/w500/{{ $movie->poster_path }}"
                                alt="{{ $movie->title }}">
                            <div class="cache"></div>
                        </div>

                        {{-- <small>{{ $movie->id }}</small> --}}


                        <form action="{{ Route('movies.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                            <button type="submit" id="addlist-btn" class="btn">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        </form>

                        <form action="{{ Route('movies.detail_tmdb', ['movie' => $movie->id]) }}" method="GET">
                            @csrf
                            <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                            <input type="submit" name="see_details" value="Voir plus +" id="detail-btn">
                        </form>


                    </article>
                </a>
            @endforeach
        </section>
    @endif

@endsection
