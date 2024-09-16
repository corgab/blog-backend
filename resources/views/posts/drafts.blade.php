@extends('layouts.app')

@section('content')
<div class="container">
    {{-- <h1 class="my-4">Bozze</h1> --}}
    <div class="card my-5">
        <div class="card-header text-center">
            Bozze
        </div>
        <div class="card-body">
            <table class="table table-striped">
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
                        <td>{{ $post->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm">Modifica</a>
                            @can('publish', $post)
                                <form action="{{ route('posts.publish', $post) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Pubblica</button>
                                </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
