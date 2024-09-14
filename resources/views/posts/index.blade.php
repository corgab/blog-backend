@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center my-4">Posts</h1>

    @if(isset($posts) && $posts->count() > 0)
    <div class="mb-3">
        <a href="{{ route('posts.create') }}" class="btn btn-primary">Create New Post</a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                {{-- <th scope="col">#</th> --}}
                <th scope="col">Title</th>
                <th scope="col">Tags</th>
                @can('approve posts')
                <th scope="col">Status</th>
                @endcan
                <th scope="col" colspan="3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($posts as $post)
            <tr>
                {{-- <th scope="row">{{ $post->id }}</th> --}}
                <td scope="row">{{ $post->title }}</td>
                <td>
                    @foreach($post->tags as $tag)
                        <span class="text-bg-secondary badge">{{ $tag->name }}</span>
                    @endforeach
                </td>
                @can('approve posts')
                <td>
                    @if ($post->status === 'draft')
                        <span class="badge text-bg-warning">Draft</span>
                    @else
                        <span class="badge text-bg-success">Published</span>
                    @endif
                </td>
                @endcan
                <td><a href="{{ route('posts.show', $post) }}" class="btn btn-primary ">View</a></td>
                <td><a href="{{ route('posts.edit', $post) }}" class="btn btn-warning ">Edit</a></td>
                <td>
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo post?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @else
    <h2 class="text-center my-4">You haven't created any posts yet</h2>
    @endif
</div>
@endsection
