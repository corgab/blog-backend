@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="text-center mb-4">{{ __('Create a new post') }}</h1>

        @if ($errors->has('sections'))
            <div class="alert alert-danger">
                {{ $errors->first('sections') }}
            </div>
        @endif

        <form id="post-form" action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">
                <div class="col-lg-8 col-md-12">
                    {{-- Titolo --}}
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title" value="{{ old('title') }}" placeholder={{ __('Title') }}>
                        <label for="title">{{ __('Title') }}</label>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-2">
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                            placeholder="{{ __('Description') }}" rows="2" required>{{ old('description') }}</textarea>
                        <label for="description">{{ __('Description') }}</label>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Sezioni --}}
                    <h4 class="mb-3 text-secondary">Sezioni</h4>
                    <div id="sections-container">
                        {{-- Prima sezione predefinita --}}
                        <div class="card mb-3 section-block">
                            <div class="card-body">
                                {{-- Titolo della prima sezione --}}
                                <div class="form-floating mb-2">
                                    <input type="text"
                                        class="form-control @error('sections.0.title') is-invalid @enderror"
                                        name="sections[0][title]" value="{{ old('sections.0.title') }}"
                                        placeholder={{ __('Title') }}>
                                    <label for="section-title-0">{{ __('Title') }}</label>
                                    @error('sections.0.title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Contenuto della prima sezione --}}
                                <div class="form-floating mb-2">
                                    <textarea class="form-control @error('sections.0.content') is-invalid @enderror" name="sections[0][content]"
                                        placeholder="Contenuto della sezione" rows="4">{{ old('sections.0.content') }}</textarea>
                                    <label for="section-content-0">{{ __('Content') }}</label>
                                    @error('sections.0.content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Immagine della prima sezione --}}
                                <div class="mb-2">
                                    <label for="section-image-0" class="form-label">{{ __('Image') }}</label>
                                    <input class="form-control @error('sections.0.image') is-invalid @enderror"
                                        type="file" id="section-image-0" name="sections[0][image]">
                                    @error('sections.0.image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Le altre sezioni --}}
                        @foreach (old('sections', []) as $index => $section)
                            @if ($index > 0)
                                <div class="card mb-3 section-block">
                                    <div class="card-body">
                                        <div class="form-floating mb-2">
                                            <input type="text"
                                                class="form-control @error('sections.' . $index . '.title') is-invalid @enderror"
                                                name="sections[{{ $index }}][title]"
                                                value="{{ $section['title'] ?? '' }}" placeholder={{ __('Title') }}>
                                            <label for="section-title-{{ $index }}">{{ __('Title') }}</label>
                                            @error('sections.' . $index . '.title')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="form-floating mb-2">
                                            <textarea class="form-control @error('sections.' . $index . '.content') is-invalid @enderror"
                                                name="sections[{{ $index }}][content]" placeholder={{ __('Content') }} rows="4">{{ $section['content'] ?? '' }}</textarea>
                                            <label for="section-content-{{ $index }}">{{ __('Content') }}</label>
                                            @error('sections.' . $index . '.content')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- Immagine della sezione --}}
                                        <div class="mb-2">
                                            <label for="section-image-{{ $index }}"
                                                class="form-label">{{ __('Image') }}</label>
                                            <input
                                                class="form-control @error('sections.' . $index . '.image') is-invalid @enderror"
                                                type="file" id="section-image-{{ $index }}"
                                                name="sections[{{ $index }}][image]">
                                            @error('sections.' . $index . '.image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <button type="button"
                                            class="btn btn-danger btn-sm mt-2 remove-section">{{ __('Remove') }}</button>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- Pulsante per aggiungere nuove sezioni --}}
                    <button type="button" id="add-section"
                        class="btn btn-outline-primary mb-4">{{ __('Add') }}</button>
                </div>

                <div class="col-lg-4 col-md-12">
                    {{-- Tags --}}
                    <h5 class="mb-3">Tags</h5>
                    <div class="row">
                        <div class="col row row-cols-2">
                            @foreach ($tags as $tag)
                                <div class="form-check">
                                    <input class="form-check-input @error('tag_id') is-invalid @enderror" name="tag_id[]"
                                        type="checkbox" value="{{ $tag->id }}" id="tag-{{ $tag->id }}"
                                        @checked(in_array($tag->id, old('tag_id', [])))>
                                    <label class="form-check-label"
                                        for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
                                </div>
                            @endforeach
                            @error('tag_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Immagine --}}
                    <div class="my-4">
                        <label for="image" class="form-label">{{ __('Cover image') }}</label>
                        <input class="form-control @error('image') is-invalid @enderror" type="file" id="image"
                            name="image">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Featured --}}
                    <div class="form-check form-switch my-3">
                        <input type="hidden" value="0" id="featured-hidden" name="featured">
                        <input class="form-check-input" type="checkbox" role="switch" id="featured" name="featured"
                            value="1" {{ old('featured') ? 'checked' : '' }}>
                        <label class="form-check-label ms-2" for="featured">Premium</label>
                    </div>

                    {{-- Stato --}}
                    @if (Auth::user()->hasRole('author'))
                        <input type="hidden" name="status" value="draft">
                    @else
                        <div class="form-floating mb-4">
                            <select class="form-select @error('status') is-invalid @enderror" id="status"
                                name="status">
                                <option value="draft" @selected(old('status') == 'draft')>{{ __('Draft') }}</option>
                                <option value="published" @selected(old('status') == 'published')>{{ __('Published') }}</option>
                            </select>
                            <label for="status">{{ __('Status') }}</label>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                </div>
            </div>
        </form>

        {{-- Pulsante Salva sempre visibile --}}
        <div class="text-center fixed-bottom py-2">
            <button type="submit" form="post-form" class="btn btn-primary">{{ __('Save') }}</button>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let sectionIndex = {{ count(old('sections', [])) > 0 ? count(old('sections', [])) : 1 }}; // Inizia da 1

        // Aggiungi nuova sezione
        document.getElementById('add-section').addEventListener('click', function() {
            const newSection = document.createElement('div');
            newSection.classList.add('card', 'mb-3', 'section-block');
            newSection.innerHTML = `
            <div class="card-body">
                <div class="form-floating mb-2">
                    <input type="text" class="form-control" name="sections[${sectionIndex}][title]" placeholder={{ __('Title') }}>
                    <label for="section-title-${sectionIndex}">{{ __('Title') }}</label>
                </div>
                <div class="form-floating mb-2">
                    <textarea class="form-control" name="sections[${sectionIndex}][content]" placeholder={{ __('Content') }} rows="4"></textarea>
                    <label for="section-content-${sectionIndex}">{{ __('Content') }}</label>
                </div>
                <div class="mb-2">
                    <label for="section-image-${sectionIndex}" class="form-label">{{ __('Image') }}</label>
                    <input class="form-control" type="file" id="section-image-${sectionIndex}" name="sections[${sectionIndex}][image]">
                </div>
                <button type="button" class="btn btn-danger btn-sm mt-2 remove-section">{{ __('Remove') }}</button>
            </div>
        `;
            document.getElementById('sections-container').appendChild(newSection);
            sectionIndex++;
        });

        // Rimozione sezione aggiuntiva
        document.getElementById('sections-container').addEventListener('click', function(event) {
            if (event.target.classList.contains('remove-section')) {
                event.target.closest('.section-block').remove();
            }
        });
    </script>
@endsection
