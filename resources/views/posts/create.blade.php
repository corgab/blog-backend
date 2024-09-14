@extends('layouts.app')

@section('content')
    <h1 class="text-center text-body">Creazione Post</h1>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
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
        {{-- Featured --}}
        <div class="form-check form-switch form-check-reverse">
            <input type="hidden" value="0" id="featured-hidden" name="featured">
            <input class="form-check-input" type="checkbox" role="switch" id="featured" name="featured" value="1" {{ old('featured') ? 'checked' : '' }}>
            <label class="form-check-label" for="featured">Featured</label>
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

        {{-- Image --}}
        <div class="mb-3">
            <label for="image" class="form-label">Image</label>
            <input class="form-control" type="file" id="image" name="image">
        </div>

        {{-- Status --}}
        @if (Auth::user()->hasRole('author'))
            <!-- Gli autori possono solo salvare come bozza -->
            <input type="hidden" name="status" value="draft">
        @else
            <!-- Gli editor e amministratori possono scegliere lo stato -->
            <div class="form-floating mb-3">
                <select class="form-select" id="status" name="status">
                    <option value="draft" @selected(old('status') == 'draft')>Bozza</option>
                    <option value="published" @selected(old('status') == 'published')>Pubblicato</option>
                </select>
                <label for="status">Status</label>
            </div>
        @endif

        <button type="submit" class="btn btn-primary">Invia</button>
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
