@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center my-4">POSTS</h1>
        @if (session('errMessage'))
            <div class="alert alert-danger text-center" role="alert">
                {{ session('errMessage') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success text-center" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (isset($posts) && $posts->count() > 0)
            <div class="row">
                @foreach ($posts as $post)
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
                                    @foreach ($post->tags as $tag)
                                        <span class="badge text-bg-dark me-1">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                                <div class="mb-3">
                                    <small class="badge
                                                @if ($post->status === 'published') text-bg-success
                                                @elseif ($post->status === 'draft') text-bg-warning
                                                @elseif ($post->status === 'approved') text-bg-primary
                                                @elseif ($post->status === 'archived') text-bg-dark
                                                @else text-bg-secondary
                                                @endif">
                                        {{ $post->status }}
                                    </small>
                                </div>
                            </div>

                            <!-- Action buttons -->
                            <div class="card-footer d-flex justify-content-between align-items-center">
                                <a href="{{ route('posts.show', $post) }}"
                                    class="btn btn-outline-dark">{{ __('View') }}</a>
                                <a href="{{ route('posts.edit', $post) }}"
                                    class="btn btn-outline-warning">{{ __('Edit') }}</a>
                                <form action="{{ route('posts.destroy', $post) }}" method="POST"
                                    onsubmit="return confirm('Sei sicuro di voler eliminare questo post?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">{{ __('Delete') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <h3 class="text-center my-4">{{ __('You don\'t have any posts yet') }}</h3>
            <a href="{{ route('posts.create') }}" class="btn btn-primary">{{ __('Create') }}</a>
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
