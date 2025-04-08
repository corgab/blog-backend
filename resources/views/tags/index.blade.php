@extends('layouts.app')

@section('content')
    <h1 class="text-center">TAGS</h1>

    @can('manage tags')
        <a href="{{ route('tags.create') }}" class="btn btn-primary">Create</a>
    @endcan

    <table class="table">
        <thead>
            <tr>
                <th scope="col">Name</th>
                <th scope="col">image</th>
                <th scope="col">Posts</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tags as $tag)
                <tr>
                    <th scope="row">{{ $tag->name }}</th>
                    @if ($tag->image)
                        <td>immagine</td>
                    @else
                        <td>nessun immagine</td>
                    @endif
                    <td>{{ $tag->posts_count }}</td>
                    <td>
                        @can('manage tags')
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
