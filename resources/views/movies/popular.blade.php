@extends('base')

@section('title', 'Films Populaires')

@section('content')
    {{-- <h1>{{ $page_title }}</h1> --}}



    @if (session('status'))
        <div class="alert alert-success" style="color: green;font-weight: bold;">
            {{ session('status') }}
        </div>
    @endif


    @if ($movies_data->results)
        <section class="box_movies">
            @foreach ($movies_data->results as $movie)
                <a href="{{ route('movies.detail_tmdb', ['movie' => $movie->id]) }}">
                    <article>
                        {{-- <h2>{{ $movie->title }}</h2> --}}
                        <div class="box_poster">
                            <img width="100%" src="https://image.tmdb.org/t/p/w500/{{ $movie->poster_path }}"
                                alt="{{ $movie->title ?? $movie->name }}">
                        </div>
                        {{-- <small>{{ $movie->id }}</small> --}}

                        <form action="{{ Route('movies.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                            <button type="submit" id="addlist-btn" class="btn">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        </form>

                        <form action="{{ Route('movies.detail', ['movie' => $movie->id]) }}" method="GET">
                            @csrf
                            <input type="hidden" name="movie_id" value="{{ $movie->id }}">
                            <input type="submit" name="see_details" value="Voir plus +" id="detail-btn">
                        </form>


                    </article>
                </a>
            @endforeach
        </section>

        <div class="pagination">
            @for ($i = 1; $i <= 10; $i++)
                <a href="{{ route('movies.popular', ['page' => $i]) }}" class="{{ $i == $current_page ? 'active' : '' }}">
                    {{ $i }}
                </a>
            @endfor
        </div>
    @endif

@endsection
