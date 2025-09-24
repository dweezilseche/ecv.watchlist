@extends('base')

@section('title', 'Séries Populaires')

@section('content')
    {{-- <h1>{{ $page_title }}</h1> --}}



    @if (session('status'))
        <div class="alert alert-success" style="color: green;font-weight: bold;">
            {{ session('status') }}
        </div>
    @endif


    @if ($series_data->results)
        <section class="box_movies no_padding">
            @foreach ($series_data->results as $serie)
                <a href="{{ route('series.detail_tmdb', ['serie' => $serie->id]) }}">
                    <article>

                        <div class="box_poster">
                            <img width="100%" src="https://image.tmdb.org/t/p/w500/{{ $serie->poster_path }}"
                                alt="{{ $serie->title ?? $serie->name }}">
                            <div class="cache"></div>
                        </div>

                        <form action="{{ Route('series.detail_tmdb', ['serie' => $serie->id]) }}" method="GET">
                            @csrf
                            <input type="hidden" name="serie_id" value="{{ $serie->id }}">
                            <input type="submit" name="see_details" value="Voir plus +" id="detail-btn">
                        </form>


                        <form action="{{ Route('series.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="serie_id" value="{{ $serie->id }}">
                            <button type="submit" id="addlist-btn" class="btn">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        </form>


                    </article>
                </a>
            @endforeach
        </section>

        <div class="pagination">
            @for ($i = 1; $i <= 10; $i++)
                <a href="{{ route('series.popular', ['page' => $i]) }}" class="{{ $i == $current_page ? 'active' : '' }}">
                    {{ $i }}
                </a>
            @endfor
        </div>
    @endif

@endsection
