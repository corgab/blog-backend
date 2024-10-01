@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="text-center mb-4">Modifica Post</h1>

        @if ($errors->has('sections'))
            <div class="alert alert-danger">
                {{ $errors->first('sections') }}
            </div>
        @endif

        <form id="post-form" action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-8 col-md-12">
                    {{-- Titolo --}}
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title" value="{{ old('title', $post->title) }}" placeholder="Scrivi il titolo qui"
                            required>
                        <label for="title">Titolo</label>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Sezioni --}}
                    <h4 class="mb-3 text-secondary">Sezioni</h4>
                    <div id="sections-container">
                        @foreach (old('sections', $post->sections) as $index => $section)
                            <div class="card mb-3 section-block">
                                <div class="card-body">
                                    {{-- Input hidden per l'ID della sezione --}}
                                    @if (isset($section['id']))
                                        <input type="hidden" name="sections[{{ $index }}][id]"
                                            value="{{ $section['id'] }}">
                                    @endif

                                    <div class="form-floating mb-2">
                                        <input type="text"
                                            class="form-control @error('sections.' . $index . '.title') is-invalid @enderror"
                                            name="sections[{{ $index }}][title]"
                                            value="{{ old('sections.' . $index . '.title', $section['title'] ?? '') }}"
                                            placeholder="Titolo della sezione" required>
                                        <label for="section-title-{{ $index }}">Titolo della sezione</label>
                                        @error('sections.' . $index . '.title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-floating mb-2">
                                        <textarea class="form-control @error('sections.' . $index . '.content') is-invalid @enderror"
                                            name="sections[{{ $index }}][content]" placeholder="Contenuto della sezione" rows="4" required>{{ old('sections.' . $index . '.content', $section['content'] ?? '') }}</textarea>
                                        <label for="section-content-{{ $index }}">Contenuto della sezione</label>
                                        @error('sections.' . $index . '.content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-2">
                                        <label for="section-image-{{ $index }}" class="form-label">Immagine della
                                            sezione</label>
                                        <input
                                            class="form-control @error('sections.' . $index . '.image') is-invalid @enderror"
                                            type="file" id="section-image-{{ $index }}"
                                            name="sections[{{ $index }}][image]">
                                        @error('sections.' . $index . '.image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    @if (isset($section['images']) && $section['images']->isNotEmpty())
                                        <div class="mt-2">
                                            @foreach ($section['images'] as $image)
                                                <img src="{{ url('storage/' . $image->path) }}"
                                                    alt="Immagine della sezione" class="img-fluid mb-2"
                                                    style="max-height: 200px; object-fit: cover;">
                                            @endforeach
                                        </div>
                                    @endif
                                    <button type="button" class="btn btn-danger btn-sm mt-2 remove-section">Rimuovi
                                        Sezione</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add-section" class="btn btn-outline-primary mb-4">Aggiungi Sezione</button>
                </div>

                <div class="col-lg-4 col-md-12">
                    {{-- Tags --}}
                    <h5 class="mb-3">Tags</h5>
                    <div class="row">
                        <div class="col row row-cols-2">
                            @foreach ($tags as $tag)
                                <div class="form-check">
                                    <input @checked($post->tags->contains($tag->id))
                                        class="form-check-input @error('tag_id') is-invalid @enderror" name="tag_id[]"
                                        type="checkbox" value="{{ $tag->id }}" id="tag-{{ $tag->id }}">
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
                        <label for="image" class="form-label">Copertina</label>
                        @foreach ($post->images as $image)
                            @if ($image->is_featured == 1)
                                <div class="mb-3">
                                    <img src="{{ url('storage/' . $image->path) }}" alt="Copertina Corrente"
                                        class="img-fluid mb-2" style="max-height: 200px; object-fit: cover;">
                                </div>
                            @endif
                        @endforeach
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
                            value="1" {{ old('featured', $post->featured) ? 'checked' : '' }}>
                        <label class="form-check-label ms-2" for="featured">In Evidenza</label>
                    </div>

                    {{-- Stato --}}
                    @if (Auth::user()->hasRole('author'))
                        <input type="hidden" name="status" value="draft">
                    @else
                        <div class="form-floating mb-4">
                            <select class="form-select @error('status') is-invalid @enderror" id="status"
                                name="status">
                                <option value="draft" @selected(old('status', $post->status) == 'draft')>Bozza</option>
                                <option value="published" @selected(old('status', $post->status) == 'published')>Pubblicato</option>
                            </select>
                            <label for="status">Stato</label>
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
            <button type="submit" form="post-form" class="btn btn-primary">Salva</button>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let sectionIndex = @json(count(old('sections', $post->sections)));

        document.getElementById('add-section').addEventListener('click', function() {
            const newSection = document.createElement('div');
            newSection.classList.add('card', 'mb-3', 'section-block');
            newSection.innerHTML = `
                <div class="card-body">
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" name="sections[${sectionIndex}][title]" placeholder="Titolo della sezione" required>
                        <label for="section-title-${sectionIndex}">Titolo della sezione</label>
                    </div>
                    <div class="form-floating mb-2">
                        <textarea class="form-control" name="sections[${sectionIndex}][content]" placeholder="Contenuto della sezione" rows="4" required></textarea>
                        <label for="section-content-${sectionIndex}">Contenuto della sezione</label>
                    </div>
                    <div class="mb-2">
                        <label for="section-image-${sectionIndex}" class="form-label">Immagine della sezione</label>
                        <input class="form-control" type="file" id="section-image-${sectionIndex}" name="sections[${sectionIndex}][image]">
                    </div>
                    <button type="button" class="btn btn-danger btn-sm mt-2 remove-section">Rimuovi Sezione</button>
                </div>
            `;
            document.getElementById('sections-container').appendChild(newSection);
            sectionIndex++;
        });

        // Rimuovi sezione
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-section')) {
                const sectionBlock = e.target.closest('.section-block');
                if (sectionBlock) {
                    sectionBlock.remove();
                }
            }
        });
    </script>
@endsection
