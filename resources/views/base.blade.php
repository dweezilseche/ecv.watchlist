<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>App Name - @yield('title')</title>
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="{{ asset('css/fonts/font.css') }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">


</head>

<body>
    <nav>

        @if (Route::currentRouteName() === 'home' || Route::currentRouteName() === 'genres.genre')
            <section id="section_genre">
                @isset($genresWithCount)
                    <select name="genre" id="genre"
                        onchange="if(this.value) window.location.href='{{ url('/genres') }}/'+this.value;">
                        <option value="">Choisir un genre</option>
                        @foreach ($genresWithCount as $genre)
                            <option value="{{ $genre->id }}">
                                {{ $genre->name }} ({{ $genre->movies_count }})
                            </option>
                        @endforeach
                    </select>
                @endisset
            </section>
        @endif



        @if (Route::currentRouteName() === 'movies.detail_tmdb' ||
                Route::currentRouteName() === 'series.detail_tmdb' ||
                Route::currentRouteName() === 'movies.detail' ||
                Route::currentRouteName() === 'series.detail' ||
                Route::currentRouteName() === 'movies.search' ||
                Route::currentRouteName() === 'series.search')
            <div id="back_btn">
                <a href="{{ url()->previous() }}"><i class="fa-solid fa-chevron-left"></i> Retour</a>
            </div>
        @endif



        @if (request()->routeIs('movies.popular', 'series.popular', 'movies.top', 'series.top'))
            <div class="switcher">
                @if (request()->routeIs('*.popular'))
                    <a href="{{ route('movies.popular') }}"
                        class="{{ request()->routeIs('movies.*') ? 'active' : '' }}">Films</a>
                    <a href="{{ route('series.popular') }}"
                        class="{{ request()->routeIs('series.*') ? 'active' : '' }}">Séries</a>
                @elseif (request()->routeIs('*.top'))
                    <a href="{{ route('movies.top') }}"
                        class="{{ request()->routeIs('movies.*') ? 'active' : '' }}">Films</a>
                    <a href="{{ route('series.top') }}"
                        class="{{ request()->routeIs('series.*') ? 'active' : '' }}">Séries</a>
                @endif
            </div>
        @endif

        {{-- Barre de recherche --}}
        @if (Route::currentRouteName() === 'movies.popular' ||
                Route::currentRouteName() === 'movies.top' ||
                Route::currentRouteName() === 'movies.search' ||
                Route::currentRouteName() === 'movies.detail_tmdb' ||
                Route::currentRouteName() === 'movies.detail')
            <form action="{{ Route('movies.search') }}" method="GET" id="search-form">
                <input type="text" name="search" placeholder="Rechercher un film" id="search-btn">
                <button type="submit" id="submit-search-btn">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        @elseif (Route::currentRouteName() === 'series.popular' ||
                Route::currentRouteName() === 'series.top' ||
                Route::currentRouteName() === 'series.search' ||
                Route::currentRouteName() === 'series.detail_tmdb' ||
                Route::currentRouteName() === 'series.detail')
            <form action="{{ Route('series.search') }}" method="GET" id="search-form">
                <input type="text" name="search" placeholder="Rechercher une série" id="search-btn">
                <button type="submit" id="submit-search-btn">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        @elseif (Route::currentRouteName() === 'home')
            <form action="{{ Route('movies.search') }}" method="GET" id="search-form">
                <input type="text" name="search" placeholder="Rechercher un film ou une série" id="search-btn"
                    class="larger">
                <button type="submit" id="submit-search-btn">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </form>
        @endif


        <ul>
            <li>
                <a href="{{ route('home') }}"
                    class="{{ request()->routeIs('home') || request()->routeIs('movies.detail_tmdb') || request()->routeIs('series.detail_tmdb') || request()->routeIs('movies.detail') || request()->routeIs('series.detail') ? 'active' : '' }}">Ma
                    liste</a>
            </li>

            {{-- Actif si movies.popular OU series.popular --}}
            <li>
                <a href="{{ route('movies.popular') }}"
                    class="{{ request()->routeIs('*.popular') || request()->routeIs('*.detail_tmdb') || request()->routeIs('*.detail') ? 'active' : '' }}">
                    Populaire
                </a>
            </li>

            {{-- Actif si movies.top OU series.top --}}
            <li>
                <a href="{{ route('movies.top') }}"
                    class="{{ request()->routeIs('*.top') || request()->routeIs('*.detail_tmdb') || request()->routeIs('*.detail') ? 'active' : '' }}">
                    Top
                </a>
            </li>

            <li>
                <a href="{{ route('movies.index') }}"
                    class="{{ request()->routeIs('movies.index') || request()->routeIs('movies.detail_tmdb') || request()->routeIs('series.detail_tmdb') || request()->routeIs('movies.detail') || request()->routeIs('series.detail') ? 'active' : '' }}">Profil</a>
            </li>
        </ul>
    </nav>

    <div id="app">
        @yield('content')
    </div>
</body>

</html>
