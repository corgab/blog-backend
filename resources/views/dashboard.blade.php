@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Success Message -->
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Dashboard Welcome -->
            <div class="mb-4">
                <h1 class="display-4 text-center">Benvenuto, <span class="text-primary">{{ $user->name }}</span></h1>
            </div>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-center gap-3 mb-4">
                <a href="{{ route('posts.index') }}" class="btn btn-primary">Posts</a>
                @can('manage tags')
                <a href="{{ route('tags.index') }}" class="btn btn-secondary">Tags</a>
                @endcan
            </div>

            <!-- Dashboard Statistics -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="p-3 border bg-light rounded text-center">
                        <h5>{{ $totalPosts }}</h5>
                        <p>Post Totali</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border bg-light rounded text-center">
                        <h5>{{ $totalTags }}</h5>
                        <p>Tags Totali</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border bg-light rounded text-center">
                        <h5>{{ $totalDrafts }}</h5>
                        <p>Bozze</p>
                    </div>
                </div>
            </div>

            <!-- Latest Posts -->
            @if($posts->isNotEmpty())
                <h4 class="mb-3">Ultimi Post</h4>
                <div class="list-group">
                    @foreach($posts as $post)
                        <a href="{{ route('posts.show', $post) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="mb-1">{{ $post->title }}</h5>
                                    <p class="mb-1">
                                        @foreach($post->tags as $tag)
                                            <span class="badge bg-secondary">{{ $tag->name }}</span>
                                        @endforeach
                                    </p>
                                    <small class="text-muted">{{ ucfirst($post->status) }}</small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted">by {{ $post->user->name }}</small>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <p class="text-center">Nessun post trovato. <a href="{{ route('posts.create') }}" class="btn btn-primary">Crea il tuo primo post</a></p>
            @endif
        </div>
    </div>
</div>
@endsection
