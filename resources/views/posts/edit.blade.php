@extends('layouts.app')
@section('head')
    <script src="https://cdn.tiny.cloud/1/lsgip8eauvxkiytzlbl9na7oqlwbgk2fzcopym7zed2ot006/tinymce/5/tinymce.min.js">
    </script>
@endsection

@section('content')
    <div class="container py-4">
        <h1 class="text-center mb-4">{{ __('Edit') }} Post</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('warn'))
            <div class="alert alert-warning">
                {{ session('warn') }}
            </div>
        @endif

        @if ($errors->has('sections'))
            <div class="alert alert-danger">
                {{ $errors->first('sections') }}
            </div>
        @endif

        {{-- <div class="alert alert-warning text-center text-decoration-underline link-offset-2" role="alert">
            {{ __('When you need to add an image to a section, first save the post and then insert the image') }}
        </div> --}}

        <form id="post-form" action="{{ route('posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-8 col-md-12">
                    {{-- Titolo --}}
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title"
                            name="title" value="{{ old('title', $post->title) }}" placeholder={{ __('Title') }}
                            required>
                        <label for="title">{{ __('Title') }}</label>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-floating mb-2">
                        <textarea class="form-control @error('content') is-invalid @enderror" name="content" placeholder="{{ __('Content') }}"
                            rows="2" required>{{ old('content', $post->content) }}</textarea>
                        {{-- <label for="description">{{ __('Description') }}</label> --}}
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-lg-4 col-md-12">
                    {{-- Categorie --}}
                    <h5 class="mb-3">Categorie</h5>
                    <div class="row row-cols-2">
                        @foreach ($tags as $tag)
                            <div class="form-check col">
                                <input @checked(in_array($tag->id, old('tag_id', $post->tags->pluck('id')->toArray())))
                                    class="form-check-input @error('tag_id') is-invalid @enderror" name="tag_id[]"
                                    type="checkbox" value="{{ $tag->id }}" id="tag-{{ $tag->id }}">
                                <label class="form-check-label" for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
                            </div>
                        @endforeach
                        @error('tag_id')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Immagine --}}
                    @if ($post->image)
                        <div>
                            <img src={{ $post->image }} alt="Post Image" class="img-fluid">
                        </div>
                        <div class="my-4">
                            <label for="image" class="form-label">{{ __('Cover image') }}</label>
                            <input class="form-control @error('image') is-invalid @enderror" type="file" id="image"
                                name="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <div class="my-4">
                            <label for="image" class="form-label">{{ __('Cover image') }}</label>
                            <input class="form-control @error('image') is-invalid @enderror" type="file" id="image"
                                name="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    {{-- Featured --}}
                    <div class="form-check form-switch my-3">
                        <input type="hidden" value="0" id="featured-hidden" name="featured">
                        <input class="form-check-input" type="checkbox" role="switch" id="featured" name="featured"
                            value="1" {{ old('featured', $post->featured) ? 'checked' : '' }}>
                        <label class="form-check-label ms-2" for="featured">Featured</label>
                    </div>

                    {{-- Stato --}}
                    @if (Auth::user()->hasRole('author'))
                        <div class="form-floating mb-4">
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>
                                    {{ __('Draft') }}</option>
                                <option value="review"
                                    {{ old('status', $post->status) == 'review' ? 'selected' : '' }}>
                                    In Revisione</option>
                            </select>
                            <label for="status">{{ __('Status') }}</label>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @else
                        <div class="form-floating mb-4">
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>
                                    {{ __('Draft') }}</option>
                                <option value="approved"
                                    {{ old('status', $post->status) == 'approved' ? 'selected' : '' }}>
                                    Approvato</option>
                            </select>
                            <label for="status">{{ __('Status') }}</label>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                    {{-- Description --}}
                    <div class="form-floating mb-2">
                        <textarea class="form-control @error('description') is-invalid @enderror" name="description"
                            placeholder="{{ __('Description') }}" rows="2">{{ old('description', $post->description) }}</textarea>
                        <label for="description">{{ __('Description') }}</label>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    {{-- META --}}
                    <div>
                        <label for="meta_description" class="form-label">{{ __('Meta Description') }}</label>
                        <input type="text" class="form-control" id="meta_description" name="meta_description"
                            @error('meta_description') is-invalid @enderror
                            value="{{ old('meta_description', $post->meta_description) }}"
                            placeholder={{ __('Meta Description') }}>
                    </div>

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
        document.addEventListener("DOMContentLoaded", function() {
            tinymce.init({
                selector: 'textarea[name=content]',
                plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
                toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help | image',
                height: 600,
                convert_urls: false,
                relative_urls: false,
                remove_script_host: false,
                file_picker_callback: function(callback, value, meta) {
                    if (meta.filetype == 'image') {
                        var input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/*');
                        input.click();

                        input.onchange = function() {
                            var file = input.files[0];
                            var reader = new FileReader();
                            reader.onload = function(e) {
                                // Carica l'immagine al server
                                var formData = new FormData();
                                formData.append('image', file);

                                // Fai la richiesta di upload dell'immagine
                                fetch("{{ route('posts.uploadImage') }}", {
                                        method: 'POST',
                                        headers: {
                                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                        },
                                        body: formData
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            // Inserisci l'immagine nel contenuto dell'editor
                                            callback(data.imageUrl, {
                                                alt: file.name
                                            });
                                        } else {
                                            alert("Errore nel caricamento dell'immagine.");
                                        }
                                    })
                                    .catch(error => {
                                        console.error(error);
                                        alert("Errore nel caricamento dell'immagine.");
                                    });
                            };
                            reader.readAsDataURL(file);
                        };
                    }
                },
                setup: function(editor) {
                    editor.on('change', function() {
                        tinymce
                            .triggerSave(); // Sincronizza il contenuto TinyMCE con il <textarea>
                    });
                }
            });


            // Assicura che i dati vengano aggiornati prima dell'invio del form
            document.getElementById("post-form").addEventListener("submit", function(event) {
                tinymce.triggerSave();
                let content = textarea.value.trim();

            });
        });
    </script>
@endsection
