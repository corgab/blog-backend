@extends('layouts.app')

@section('content')

<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">nome</th>
            <th scope="col">content</th>
            <th scope="col">creator</th>
            <th scope="col">read time</th>
            <th scope="col">difficulty</th>
            <th scope="col">featured</th>
            <th scope="col">created</th>
            <th scope="col">tags</th>
            <th scope="col">technologies</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row">{{$post->id}}</th>
            <td>{{$post->title}}</td>
            <td>{{$post->content}}</td>
            <td>{{$post->user->name}}</td>
            <td>{{ $post->reading_time }}</td>
            <td>{{$post->difficulty}}</td>
            <td>{{$post->featured}}</td>
            <td>{{$post->created_at->translatedFormat('d F Y ')}}</td>
            <td>
                @foreach($post->tags as $tag)
                {{$tag->name}}
                @endforeach
            </td>
            <td>
                @foreach($post->technologies as $technology)
                {{$technology->name}}
                @endforeach
            </td>
        </tr>
    </tbody>
</table>

@endsection