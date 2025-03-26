@extends('layouts.app')
@section('head')
    <script src="https://cdn.tiny.cloud/1/lsgip8eauvxkiytzlbl9na7oqlwbgk2fzcopym7zed2ot006/tinymce/5/tinymce.min.js">
    </script>
@endsection

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
                        <textarea class="form-control @error('content') is-invalid @enderror" name="content" placeholder="{{ __('Content') }}"
                            rows="2">{{ old('content') }}</textarea>
                        {{-- <label for="description">{{ __('Description') }}</label> --}}
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
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
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
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
        document.addEventListener("DOMContentLoaded", function() {
            tinymce.init({
                selector: 'textarea[name=content]',
                plugins: 'advlist autolink lists link image charmap print preview anchor searchreplace visualblocks code fullscreen insertdatetime media table paste code help wordcount',
                toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | image | help',
                height: 600,
                convert_urls: false,
                relative_urls: false,
                remove_script_host: false,
                file_picker_callback: function(callback, value, meta) {
                    if (meta.filetype === 'image') {
                        var input = document.createElement('input');
                        input.setAttribute('type', 'file');
                        input.setAttribute('accept', 'image/*');
                        input.click();

                        input.onchange = function() {
                            var file = input.files[0];
                            var formData = new FormData();
                            formData.append('image', file);

                            // Carica l'immagine sul server
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
                                        // Inserisci l'immagine con URL assoluto
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
                    }
                },
                setup: function(editor) {
                    editor.on('change', function() {
                        tinymce
                            .triggerSave(); // Sincronizza il contenuto di TinyMCE con il <textarea>
                    });
                }
            });
        });
    </script>
@endsection
