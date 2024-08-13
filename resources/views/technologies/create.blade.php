@extends('layouts.app')
@section('content')

<form action="{{route('technologies.store')}}" method="POST">

    @csrf
    {{-- @method('PUT') --}}

    <div class="mb-3">
      <label for="name" class="form-label">Name</label>
      <input type="text" class="form-control" id="name" name="name">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
  </form>

@endsection