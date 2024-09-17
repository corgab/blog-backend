@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="text-center mb-4">Modifica Post</h1>

    <form id="post-form" action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-8 col-md-12">
                {{-- Titolo --}}
                <div class="form-floating mb-4">
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $post->title) }}" placeholder="Scrivi il titolo qui">
                    <label for="title">Titolo Post</label>
                    @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Sezioni --}}
                <h4 class="mb-3 text-secondary">Sezioni</h4>
                <div id="sections-container">
                    @foreach (old('sections', $post->sections->toArray()) as $index => $section)
                    <div class="card mb-3 section-block">
                        <div class="card-body">
                            {{-- Titolo Sezione --}}
                            <div class="form-floating mb-2">
                                <input type="text" name="sections[{{ $index }}][title]" class="form-control @error('sections.' . $index . '.title') is-invalid @enderror" value="{{ old('sections.'.$index.'.title', $section['title']) }}" placeholder="Titolo della sezione">
                                <label for="sections[{{ $index }}][title]">Titolo Sezione</label>
                                @error('sections.' . $index . '.title')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Contenuto Sezione --}}
                            <div class="form-floating mb-2">
                                <textarea name="sections[{{ $index }}][content]" class="form-control @error('sections.' . $index . '.content') is-invalid @enderror" rows="4" placeholder="Contenuto della sezione">{{ old('sections.'.$index.'.content', $section['content']) }}</textarea>
                                <label for="sections[{{ $index }}][content]">Contenuto Sezione</label>
                                @error('sections.' . $index . '.content')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="button" class="btn btn-danger btn-sm mt-2 remove-section">Rimuovi Sezione</button>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="add-section" class="btn btn-outline-primary mb-4">Aggiungi Sezione</button>
            </div>

            <div class="col-lg-4 col-md-12">
                {{-- Tags --}}
                <div class="mb-4">
                    <h5>Tags</h5>
                    <div class="row">
                        <div class="col-md-6">
                            @foreach ($tags->take(ceil($tags->count() / 2)) as $tag)
                            <div class="form-check">
                                <input @checked(in_array($tag->id, old('tag_id', $post->tags->pluck('id')->toArray()))) class="form-check-input @error('tag_id') is-invalid @enderror" name="tag_id[]" type="checkbox" value="{{ $tag->id }}" id="tag-{{ $tag->id }}">
                                <label class="form-check-label" for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
                            </div>
                            @endforeach
                            @error('tag_id')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            @foreach ($tags->skip(ceil($tags->count() / 2)) as $tag)
                            <div class="form-check">
                                <input @checked(in_array($tag->id, old('tag_id', $post->tags->pluck('id')->toArray()))) class="form-check-input @error('tag_id') is-invalid @enderror" name="tag_id[]" type="checkbox" value="{{ $tag->id }}" id="tag-{{ $tag->id }}">
                                <label class="form-check-label" for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
                            </div>
                            @endforeach
                            @error('tag_id')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Immagine --}}
                <div class="mb-4">
                    <label for="image" class="form-label">Immagine</label>
                    <input class="form-control @error('image') is-invalid @enderror" type="file" id="image" name="image">
                    @error('image')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Featured - In evidenza --}}
                <div class="form-check form-switch mb-4">
                    <input type="hidden" value="0" id="featured-hidden" name="featured">
                    <input class="form-check-input @error('featured') is-invalid @enderror" type="checkbox" role="switch" id="featured" name="featured" value="1" {{ old('featured', $post->featured) ? 'checked' : '' }}>
                    <label class="form-check-label" for="featured">In evidenza</label>
                    @error('featured')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Stato --}}
                @if (Auth::user()->hasRole('author'))
                <!-- Gli autori possono solo salvare come bozza -->
                <input type="hidden" name="status" value="draft">
                @else
                <!-- Gli editori e amministratori possono scegliere lo stato -->
                <div class="form-floating mb-4">
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="draft" @selected(old('status', $post->status) == 'draft')>Bozza</option>
                        <option value="published" @selected(old('status', $post->status) == 'published')>Pubblicato</option>
                    </select>
                    <label for="status">Stato</label>
                    @error('status')
                    <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
                @endif
            </div>
        </div>
    </form>

    {{-- Pulsante Salva sempre visibile --}}
    <div class="text-center fixed-bottom bg-light py-2">
        <button type="submit" form="post-form" class="btn btn-primary btn-lg">Modifica</button>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('add-section').addEventListener('click', function() {
        const index = document.querySelectorAll('.section-block').length;
        let newSection = document.createElement('div');
        newSection.classList.add('card', 'mb-3', 'section-block');
        newSection.innerHTML = `
            <div class="card-body">
                <div class="form-floating mb-2">
                    <input type="text" name="sections[${index}][title]" class="form-control" placeholder="Titolo della sezione">
                    <label for="sections[${index}][title]">Titolo Sezione</label>
                </div>
                <div class="form-floating mb-2">
                    <textarea name="sections[${index}][content]" class="form-control" rows="4" placeholder="Contenuto della sezione"></textarea>
                    <label for="sections[${index}][content]">Contenuto Sezione</label>
                </div>
                <button type="button" class="btn btn-danger btn-sm mt-2 remove-section">Rimuovi Sezione</button>
            </div>
        `;
        document.getElementById('sections-container').appendChild(newSection);
    });

    // Rimozione delle sezioni dinamiche
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-section')) {
            e.target.closest('.section-block').remove();
        }
    });
</script>
@endsection
