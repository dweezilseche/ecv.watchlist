@extends('base')

@section('title', 'Résultat de la recherche | Séries')

@section('content')
    {{-- <h1>{{ $page_title }}</h1> --}}

    @if (session('status'))
        <div class="alert alert-success" style="color: green;font-weight: bold;">
            {{ session('status') }}
        </div>
    @endif


    @if ($series_data->results)
        <p class="search_result">"{{ $search_result }}"</p>

        <section class="box_movies results">
            @foreach ($series_data->results as $serie)
                <a href="{{ route('series.detail_tmdb', ['serie' => $serie->id]) }}">
                    <article>
                        <div class="box_poster">
                            <img width="100%" src="https://image.tmdb.org/t/p/w500/{{ $serie->poster_path }}"
                                alt="{{ $serie->title ?? $serie->name }}">
                            <div class="cache"></div>
                        </div>

                        <form action="{{ Route('series.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="serie_id" value="{{ $serie->id }}">
                            <button type="submit" id="addlist-btn" class="btn">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        </form>

                        <form action="{{ Route('series.detail', ['serie' => $serie->id]) }}" method="GET">
                            @csrf
                            <input type="hidden" name="serie_id" value="{{ $serie->id }}">
                            <input type="submit" name="see_details" value="Voir plus +" id="detail-btn">
                        </form>


                    </article>
                </a>
            @endforeach
        </section>
    @endif

    @if (!$series_data->results)
        <p class="no_exist">Cette série n'existe pas...</p>
    @else
    @endif

@endsection
