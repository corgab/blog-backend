@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <h1 class="mb-2">Trash</h1>

        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if($posts->isEmpty())
        <p>Nessun post nel Cestino</p>
        @else

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Title</th>
                    <th scope="col">Author</th>
                    <th scope="col">Eliminato il</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($posts as $post)
                    <tr>
                        <td>{{$post->title}}</td>
                        <td>{{$post->user->name}}</td>
                        <td>{{ ucfirst($post->created_at->translatedFormat('M d, Y')) }}</td>
                        <td>
                            <form action="{{ route('posts.restore', $post) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success btn-sm">Ripristina</button>
                            </form>
                            
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
@endsection
