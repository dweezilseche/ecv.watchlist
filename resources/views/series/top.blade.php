@extends('base')

@section('title', 'Séries les mieux notés')

@section('content')
    {{-- <h1>{{ $page_title }}</h1> --}}



    @if (session('status'))
        <div class="alert alert-success" style="color: green;font-weight: bold;">
            {{ session('status') }}
        </div>
    @endif


    @if ($series_data)
        <section class="box_movies no_padding">
            @foreach ($series_data as $serie)
                <a href="{{ Route('series.detail_tmdb', ['serie' => $serie->id]) }}">
                    <article>
                        {{-- <h2>{{ $movie->title }}</h2> --}}
                        <div class="box_poster">
                            <div class="ranking">
                                {{ $loop->iteration }}
                            </div>
                            <img width="100%" src="https://image.tmdb.org/t/p/w500/{{ $serie->poster_path }}"
                                alt="{{ $serie->name }}">
                            <div class="cache"></div>
                        </div>


                        <form action="{{ Route('series.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="serie_id" value="{{ $serie->id }}">
                            <button type="submit" id="addlist-btn" class="btn">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        </form>


                        {{-- <form action="{{ Route('series.detail_tmdb', ['serie' => $serie->id]) }}" method="GET">
                            @csrf
                            <input type="hidden" name="serie_id" value="{{ $serie->id }}">
                            <input type="submit" name="see_details" value="Voir plus +" id="detail-btn">
                        </form> --}}


                    </article>
                </a>
            @endforeach
        </section>
    @endif

@endsection
