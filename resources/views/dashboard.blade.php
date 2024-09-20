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

            <!-- Dashboard Statistics -->
            <div class="row mb-4 justify-content-center">
                <a href="{{ route('posts.index') }}" class="col-12 col-md text-decoration-none">
                    <div class="p-3 border bg-light rounded text-center">
                        <h5>{{ $totalPosts }}</h5>
                        <p>Post Totali</p>
                    </div>
                </a>
                @if (Auth::user()->hasRole('admin') || $user->hasRole('editor'))
                <a href="{{ route('posts.drafts') }}" class="col-12 col-md text-decoration-none">
                    <div class="p-3 border bg-light rounded text-center">
                        <h5>{{ $totalDrafts }}</h5>
                        <p>Bozze</p>
                    </div>
                </a>
                @endif
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
