@extends('layouts.app')

@section('content')
<h1>Bozze</h1>
<table>
    <thead>
        <tr>
            <th>Titolo</th>
            <th>Autore</th>
            <th>Data di Creazione</th>
            <th>Azioni</th>
        </tr>
    </thead>
    <tbody>
        @foreach($drafts as $post)
        <tr>
            <td>{{ $post->title }}</td>
            <td>{{ $post->user->name }}</td>
            <td>{{ $post->created_at }}</td>
            <td>
                <a href="{{ route('posts.edit', $post) }}">Modifica</a>
                @if (Auth::user()->can('publish', $post))
                    <form action="{{ route('posts.publish', $post) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit">Pubblica</button>
                    </form>
                @endif
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
