@extends('layouts.app')

@section('title', 'Bozze')

@section('content')
    <div class="container">
        <h1 class="text-center my-4">Drafts</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($drafts->isEmpty())
            <p>No draft posts available.</p>
        @else
            <table class="table table-striped table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Title</th>
                        <th>Created At</th>
                        <th>Author</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($drafts as $post)
                        <tr>
                            <td>{{ $post->title }}</td>
                            <td>{{ ucfirst($post->created_at->translatedFormat('M d, Y')) }}</td>
                            <td>{{ $post->user->name }}</td>
                            <td class="text-center">
                                <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm">Edit</a>
                                <a href="{{ route('posts.publish', $post) }}" class="btn btn-success btn-sm">Publish</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
