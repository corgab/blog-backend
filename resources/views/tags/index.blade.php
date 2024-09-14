@extends('layouts.app')

@section('content')
<h1 class="text-center">TAGS</h1>

@can('manage tags')
    <a href="{{ route('tags.create') }}" class="btn btn-primary">Create</a>
@endcan

<table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Name</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
    @forelse($tags as $tag)
      <tr>
        <th scope="row">{{ $tag->id }}</th>
        <td>{{ $tag->name }}</td>
        <td>
          @can('manage tags')
            {{-- <a href="{{ route('tags.edit', $tag) }}" class="btn btn-warning">Edit</a> --}}
            <form action="{{ route('tags.destroy', $tag) }}" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger" type="submit">Delete</button>
            </form>
          @endcan
        </td>
      </tr>
    @empty
      <tr>
        <td colspan="3" class="text-center">No tags found.</td>
      </tr>
    @endforelse
    </tbody>
  </table>
@endsection
