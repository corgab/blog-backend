@extends('layouts.app')

@section('content')
    <div class="container ">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Success Message -->
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Dashboard Welcome -->
                <div class="mb-2">
                    <h1 class="display-4 text-center">{{ __('Welcome') }}, <span
                            class="text-primary">{{ $user->name }}</span></h1>
                </div>

                <!-- Dashboard Statistics -->
                @if (Auth::user()->hasRole('user'))
                    <h1 class="text-center">ciao</h1> <!-- Logica utente -->
                @else
                    <div class="row mb-4 justify-content-center">
                        <a href="{{ route('posts.index') }}" class="col-12 col-md text-decoration-none">
                            <div class="p-3 border bg-light rounded text-center">
                                <h5>{{ $totalPosts }}</h5>
                                <p>{{ __('Published posts') }}</p>
                            </div>
                        </a>
                        @if ($user->hasRole('admin') || $user->hasRole('editor'))
                            <a href="{{ route('posts.approve') }}" class="col-12 col-md text-decoration-none">
                                <div class="p-3 border bg-light rounded text-center">
                                    <h5>{{ $totalDrafts }}</h5>
                                    <p>Da Approvare</p>
                                </div>
                            </a>
                        @endif
                    </div>

                    <!-- Latest Posts -->
                    @if ($posts->isNotEmpty())
                        <h4 class="mb-3">Ultimi posts</h4>
                        <div class="list-group">
                            @foreach ($posts as $post)
                                <a href="{{ route('posts.show', $post) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-1">{{ $post->title }}</h5>
                                            <p class="mb-1">
                                                @foreach ($post->tags as $tag)
                                                    <small class=>{{ $tag->name }}</small>
                                                @endforeach
                                            </p>
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
                                        <div class="text-end">
                                            <small class="text-muted">by {{ $post->user->name }}</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-center">{{ __('No posts found') }} <a href="{{ route('posts.create') }}"
                                class="btn btn-primary">{{ __('Create your first post') }}</a></p>
                    @endif
                @endif
            </div>
        </div>
    </div>
@endsection
