@extends('layouts.app')

@section('content')

<form action="{{ route('posts.update', $post) }}" method="POST" enctype="multipart/form-data">

    @csrf
    @method('PUT')

    {{-- Title --}}
    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $post->title) }}" placeholder="Write title here">
        <label for="title">Title</label>
    </div>

    {{-- Content --}}
    <div class="form-floating mb-3">
        <textarea class="form-control" placeholder="Write content here" id="content" name="content">{{ old('content', $post->content) }}</textarea>
        <label for="content">Content</label>
    </div>

    {{-- Featured --}}
    <div class="form-check form-switch form-check-reverse">
        <input type="hidden" value="0" id="featured-hidden" name="featured">
        <input class="form-check-input" type="checkbox" role="switch" id="featured" name="featured" value="1" {{ old('featured', $post->featured) ? 'checked' : '' }}>
        <label class="form-check-label" for="featured">Featured</label>
    </div>

    {{-- Status --}}
    @if (Auth::user()->hasRole('author'))
        <!-- Gli autori non possono modificare lo stato -->
        <input type="hidden" name="status" value="{{ $post->status }}">
    @else
        <!-- Gli editor e amministratori possono modificare lo stato -->
        <div class="form-floating mb-3">
            <select class="form-select" id="status" name="status">
                <option value="draft" @selected(old('status', $post->status) == 'draft')>Bozza</option>
                <option value="published" @selected(old('status', $post->status) == 'published')>Pubblicato</option>
            </select>
            <label for="status">Status</label>
        </div>
    @endif

    {{-- Tags --}}
    <div class="form-check">
        @foreach ($tags as $tag)
            <div>
                <input @checked(in_array($tag->id, old('tag_id', $post->tags->pluck('id')->toArray()))) class="form-check-input" name="tag_id[]" type="checkbox" value="{{ $tag->id }}" id="type-{{ $tag->id }}">
                <label class="form-check-label" for="type-{{ $tag->id }}">{{ $tag->name }}</label>
            </div>
        @endforeach
    </div>

    {{-- Image --}}
    <div class="mb-3">
        <label for="image" class="form-label">Image (Optional)</label>
        <input class="form-control" type="file" id="image" name="image">
        @if ($post->image)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $post->image->path) }}" alt="Current Image" style="max-width: 200px;">
            </div>
        @endif
    </div>

    <button type="submit" class="btn btn-primary">Modify</button>

</form>

@if ($errors->any())
<div class="alert alert-danger mt-3">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

@endsection
