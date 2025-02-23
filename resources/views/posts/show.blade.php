@extends('layouts.app')

@section('content')
    <div class="container mt-5">

        <!-- Post Title -->
        <div class="text-center mb-4">
            <h1>{{ $post->title }}</h1>
        </div>

        <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning mb-4"><i class="bi bi-pencil-square"></i>
            {{ __('Edit') }}</a>
        <!-- Post Metadata -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex flex-column flex-md-row justify-content-between border-bottom pb-3 mb-3">
                    <div class="fw-bold">ID:</div>
                    <div>{{ $post->id }}</div>
                </div>
                <div class="d-flex flex-column flex-md-row justify-content-between border-bottom pb-3 mb-3">
                    <div class="fw-bold">{{ __('Reading time') }}</div>
                    <div>{{ $post->reading_time }}</div>
                </div>
                <div class="d-flex flex-column flex-md-row justify-content-between border-bottom pb-3 mb-3">
                    <div class="fw-bold">Premium</div>
                    <div>
                        @if ($post->featured)
                            <span class="badge bg-success">{{ __('Yes') }}</span>
                        @else
                            <span class="badge bg-danger">No</span>
                        @endif
                    </div>
                </div>
                <div class="d-flex flex-column flex-md-row justify-content-between border-bottom pb-3 mb-3">
                    <div class="fw-bold">{{ __('Create ad') }}</div>
                    <div>{{ ucfirst($post->created_at->translatedFormat('M d, Y')) }}</div>
                </div>
                <div class="d-flex flex-column flex-md-row justify-content-between border-bottom pb-3 mb-3">
                    <div class="fw-bold">{{ __('Status') }}</div>
                    <div>{{ $post->status }}</div>
                </div>

                <!-- Post Tags -->
                <div class="d-flex flex-wrap gap-2 mt-3">
                    @foreach ($post->tags as $tag)
                        <span class="badge text-bg-success fs-6">{{ $tag->name }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        @if ($post->description)
            <h3>{{ __('Content') }}</h3>
            <div>
                {!! $post->description !!}
            </div>
        @endif


    </div>
    </div>

    </div>
@endsection
