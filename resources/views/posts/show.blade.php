@extends('layouts.app')

@section('content')
<div class="container mt-5">

    <!-- Post Title -->
    <div class="text-center mb-4">
        <h1>{{ $post->title }}</h1>
    </div>

    <!-- Post Metadata -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex flex-column flex-md-row justify-content-between border-bottom pb-3 mb-3">
                <div class="fw-bold">ID:</div>
                <div>{{ $post->id }}</div>
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between border-bottom pb-3 mb-3">
                <div class="fw-bold">Read Time:</div>
                <div>{{ $post->reading_time }} min</div>
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between border-bottom pb-3 mb-3">
                <div class="fw-bold">Featured:</div>
                <div>
                    @if($post->featured)
                        <span class="badge bg-success">Yes</span>
                    @else
                        <span class="badge bg-danger">No</span>
                    @endif
                </div>
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between border-bottom pb-3 mb-3">
                <div class="fw-bold">Created:</div>
                <div>{{ ucfirst($post->created_at->translatedFormat('M d, Y')) }}</div>
            </div>
            <!-- Post Tags -->
            <div class="d-flex flex-wrap gap-2 mt-3">
                @foreach($post->tags as $tag)
                    <span class="badge text-bg-success fs-6">{{ $tag->name }}</span>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Post Content and Images -->
    <div class="row">
        <div class="col-md-12 col-lg-6 mb-4">
            @forelse($post->images as $image)
                @if($image->is_featured)
                    <div class="position-relative">
                        <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->alt }}" class="img-fluid rounded shadow-sm">
                        <div class="position-absolute top-0 start-0 bg-dark text-white px-2 py-1 rounded-end">
                            Immagine Copertina
                        </div>
                    </div>
                    @break
                @endif
                @empty
                <a href="{{ route('posts.edit', $post) }}" class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover">Aggiungi Immagine</a>
                @endforelse
        </div>

        <div class="col-md-12 col-lg-6">
            @foreach ($sections as $index => $section)
                <h2 class="mb-3">Section {{ $index + 1 }}</h2>
                <div class="mb-4 p-4 border rounded shadow-sm">
                    @if ($section['title'])
                        <h2 class="mb-3">{{ $section['title'] }}</h2>
                    @endif
                    <div>{!! $section['content'] !!}</div>
                </div>
            @endforeach
        </div>
    </div>

</div>
@endsection
