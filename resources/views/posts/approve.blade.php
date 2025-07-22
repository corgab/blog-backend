@extends('layouts.app')

@section('title', 'Bozze')

@section('content')
    <div class="container">
        <h1 class="text-center my-4">Da Approvare</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('warn'))
            <div class="alert alert-warning">
                {{ session('warn') }}
            </div>
        @endif

        @if ($posts->isEmpty())
            <p>Non ci sono post da approvare</p>
        @else
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Titolo</th>
                        <th>Creato il</th>
                        <th>Autore</th>
                        <th class="text-center">Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td>{{ $post->title }}</td>
                            <td>{{ ucfirst($post->created_at->translatedFormat('M d, Y')) }}</td>
                            <td>{{ $post->user->name }}</td>
                            <td class="text-center">
                                <a href="{{ route('posts.show', $post) }}"
                                    class="btn btn-warning btn-sm">Visualizza</a>
                                <form action="{{ route('posts.approve.store', $post) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Approva</button>
                                </form>
                                {{-- <a href="{{ route('posts.publish', $post) }}"
                                    class="btn btn-success btn-sm">{{ __('Publish') }}</a>--}}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
