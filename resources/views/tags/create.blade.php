@extends('layouts.app')
@section('content')
    <form action="{{ route('tags.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- <div class="mb-3">
            <label for="name" class="form-label">name</label>
            <input type="text" class="form-control" id="name" name="name" value="" required>
        </div> --}}
        <div class="form-floating mb-3">
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}"
                placeholder={{ __('Name') }}>
            <label for="name">{{ __('name') }}</label>
        </div>
        <div class="mb-3">
            <input class="form-control" type="file" id="image" name="image">
        </div>
        <button type="submit" class="btn btn-primary">Send</button>
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
