@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center my-3">Posts</h1>

    @if(isset($posts) && $posts->count() > 0)
    <div class="mb-3 d-flex justify-content-end">
        <a href="{{ route('posts.create') }}" class="btn btn-dark">Create New Post</a>
    </div>

    <div class="row">
        @foreach($posts as $post)
        <div class="col-md-4 mb-4">
            <div class="card border-muted shadow-lg position-relative">
                <!-- Content area clickable -->
                <a href="{{ route('posts.show', $post) }}" class="stretched-link card-link"></a>
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="card-title mb-0">{{ $post->title }}</h5>
                        <span class="text-muted">{{ $post->user->name }}</span>
                    </div>
                    <div class="d-flex flex-wrap mb-3">
                        @foreach($post->tags as $tag)
                            <span class="badge bg-light text-dark me-1">{{ $tag->name }}</span>
                        @endforeach
                    </div>
                    @can('approve posts')
                    <div class="mb-3">
                        @if ($post->status === 'draft')
                            <span class="badge bg-secondary text-light">Draft</span>
                        @else
                            <span class="badge bg-success text-light">Published</span>
                        @endif
                    </div>
                    @endcan
                </div>
                
                <!-- Action buttons -->
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-dark">View</a>
                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-outline-warning">Edit</a>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo post?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @else
    <h2 class="text-center my-4">Non hai ancora nessun post</h2>
    <a href="{{route('posts.create')}}" class="btn btn-primary">Crea</a>
    @endif
</div>
@endsection

@push('styles')
<style>
    .card-link {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 0;
        pointer-events: none;
    }
    .card-body {
        position: relative;
        z-index: 1;
    }
    .card-footer {
        position: relative;
        z-index: 2;
        pointer-events: auto;
    }
</style>
@endpush
