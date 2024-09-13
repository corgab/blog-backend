@extends('layouts.app')

@section('content')

<form action="{{route('posts.update', $post)}}" method="POST">

    @csrf
    @method('PUT')

    {{-- Title --}}
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="title" name="title" value="{{old('title', $post->title)}}" placeholder="Write title here">
        <label for="title">Title</label>
    </div>
    {{-- Content --}}
    <div class="form-floating mb-3">
        <textarea class="form-control" placeholder="Write content here" id="content" name="content">{{ old('content', $post->content) }}</textarea>
        <label for="content">Content</label>
    </div>

    {{-- Difficulty --}}
    {{-- <div class="mb-3">
        <select class="form-select" id="difficulty" name="difficulty" multiple>
            <option value="1" {{ old('difficulty', $post->difficulty) == 1 ? 'selected' : '' }}>Easy</option>
            <option value="2" {{ old('difficulty', $post->difficulty) == 2 ? 'selected' : '' }}>Medium</option>
            <option value="3" {{ old('difficulty', $post->difficulty) == 3 ? 'selected' : '' }}>Hard</option>
        </select>
    </div> --}}
    {{-- Featured --}}
    <div class="form-check form-switch form-check-reverse">
        <input type="hidden" value="0" id="featured-hidden" name="featured">
        <input class="form-check-input" type="checkbox" role="switch" id="featured" name="featured" value="1" {{ old('featured', $post->featured) ? 'checked' : '' }}> <!-- DA FIXARE-->
        <label class="form-check-label" for="featured">Featured</label>
    </div>
    {{-- Tags --}}
    <div class="form-check">
        @foreach ($tags as $tag)
            <div>
                <input @checked(in_array($tag->id, old('tag_id', $post->tags->pluck('id')->toArray()))) class="form-check-input" name="tag_id[]" type="checkbox" value="{{ $tag->id }}" id="type-{{ $tag->id }}">
                <label class="form-check-label" for="type-{{ $tag->id }}">{{ $tag->name }}</label>
            </div>
        @endforeach
    </div>

    <button type="submit">Modify</button>

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
