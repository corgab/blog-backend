@extends('layouts.app')
@section('content')
    <h1 class="text-center text-body">Creazione Post</h1>

    <form action="{{route('posts.store')}}" method="POST">
        @csrf
        {{-- Title --}}
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Write title here">
            <label for="title">Title</label>
        </div>
        {{-- Content --}}
        <div class="form-floating mb-3">
            <textarea class="form-control" placeholder="Write content here" id="content" name="content">{{ old('content') }}</textarea>
            <label for="content">Content</label>
        </div>
        <div class="d-flex gap-4">
            {{-- Difficulty --}}
            <div class="mb-3">
                <select class="form-select" id="difficulty" name="difficulty" multiple>
                    <option selected>Select difficulty</option>
                    <option value="1">Easy</option>
                    <option value="2">Medium</option>
                    <option value="3">Hard</option>
                </select>
            </div>
            {{-- Featured --}}
            <div class="form-check form-switch form-check-reverse">
                <input type="hidden" value="0" id="featured-hidden" name="featured">
                <input class="form-check-input" type="checkbox" role="switch" id="featured" name="featured" value="1" {{ old('featured', 0) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="featured">Featured</label>
            </div>
        </div>
        {{-- Tags --}}
        <div class="form-check">
            @foreach ($tags as $tag)
                <div>
                    <input @checked(in_array($tag->id, old('tag_id', []))) class="form-check-input @error('tag_id') is-invalid @enderror" name="tag_id[]" type="checkbox" value="{{ $tag->id }}" id="type-{{ $tag->id }}">
                    <label class="form-check-label" for="type-{{ $tag->id }}">{{ $tag->name }}</label>
                </div>
            @endforeach
        </div>
        {{-- Technologies --}}
        <h1>Technologies</h1>
        <div class="form-check">
            @foreach ($technologies as $technology)
                <div>
                    <input @checked(in_array($technology->id, old('technology_id', []))) class="form-check-input @error('technology_id') is-invalid @enderror" name="technology_id[]" type="checkbox" value="{{ $technology->id }}" id="technology-{{ $technology->id }}">
                    <label class="form-check-label" for="technology-{{ $technology->id }}">{{ $technology->name }}</label>
                </div>
            @endforeach
        </div>
        
        <button type="submit">Invia</button>
    </form>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection