@extends('layouts.app')
@section('content')
    <div class="container mt-4">
        <h1 class="mb-2 text-center">{{ __('Trash') }}</h1>

        @if (session('success') && !$posts->isEmpty())
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if ($posts->isEmpty())
            <h3 class="text-center my-4">{{ __('You don\'t have any posts yet') }}</h3>
        @else
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">{{ __('Title') }}</th>
                        <th scope="col">{{ __('Author') }}</th>
                        <th scope="col">{{ __('Deleted at') }}</th>
                        <th scope="col">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td>{{ $post->title }}</td>
                            <td>{{ $post->user->name }}</td>
                            <td>{{ ucfirst($post->created_at->translatedFormat('M d, Y')) }}</td>
                            <td>
                                <form action="{{ route('posts.restore', $post) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm">{{ __('Restore') }}</button>
                                </form>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
    @endif
@endsection
