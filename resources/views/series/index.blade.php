@extends('base')

@section('title', 'Tous les films')

@section('content')
    <h1>Tous les films</h1>

    @if ($movies_data) 
        <section class="box_movies">
            <table class="index">
                <head>
                    <tr>
                        <th></th>
                        <th>#</th>
                        <th># IMDB</th>
                        <th>Name</th>
                        <th></th>
                    </tr>
                </head>
                <tbody>
                    @foreach ($movies_data as $movie)
                        <tr>
                            <td><img width="100%" src="https://image.tmdb.org/t/p/w500/{{ $movie->poster_path }}" alt="{{ $movie->title }}"></td>
                            <td>{{ $movie->id }}</td>
                            <td>{{ $movie->id_movie_tmdb }}</td>
                            <td>{{ $movie->name }}</td>
                            <td>
                                <form action="{{ Route('movies.delete', $movie->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="submit" value="Supprimer">
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    @endif
    
@endsection

