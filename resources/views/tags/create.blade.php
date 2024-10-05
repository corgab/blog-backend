@extends('layouts.app')
@section('content')
    <form action="{{ route('tags.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <button type="submit">Send</button>
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
