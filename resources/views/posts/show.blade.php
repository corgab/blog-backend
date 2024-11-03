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

        <!-- Post Content and Images -->
        <div class="row">
            <div class="col-md-12 col-lg-6 mb-4">
                @forelse($post->images as $image)
                    @if ($image->is_featured)
                        <div class="position-relative">
                            <img src="{{ url('storage/' . $image->path) }}" alt="{{ $image->alt }}"
                                class="img-fluid rounded shadow-sm">
                            <div class="position-absolute top-0 start-0 bg-dark text-white px-2 py-1 rounded-end">
                                {{ __('Cover image') }}
                            </div>
                        </div>
                    @break
                @endif
            @empty
                <a href="{{ route('posts.edit', $post) }}"
                    class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover">{{ __('Add image') }}</a>
            @endforelse
        </div>

        <div class="col-md-12 col-lg-6">
            @foreach ($sections as $index => $section)
                <h2 class="mb-3">{{ __('Section') }} {{ $index + 1 }}</h2>
                <div class="mb-4 p-4 border rounded shadow-sm">
                    <!-- Aggiungi la logica per visualizzare l'immagine -->
                    @foreach ($section->images as $image)
                        <img src="{{ url('storage/' . $image->path) }}" alt="{{ $image->alt }}" class="img-fluid">
                    @endforeach
                    @if ($section->title)
                        <h2 class="mb-3">{{ $section->title }}</h2>
                    @endif
                    <div>{!! $section->content !!}</div>

                </div>
            @endforeach

        </div>
    </div>

</div>
@endsection
