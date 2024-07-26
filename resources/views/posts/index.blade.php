@extends('layouts.app')
@section('content')

<h1 class="text-center">POSTS</h1>

<table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">nome</th>
        <th scope="col">creator</th>
        <th scope="col">featured</th>
        <th scope="col">difficulty</th>
        <th scope="col">read time</th>
        <th scope="col">created</th>
        <th scope="col">technologies</th>
        <th scope="col" colspan="3">actions</th>
      </tr>
    </thead>
    <tbody>
        @foreach($posts as $post)
        <tr>

            <th scope="row">{{$post->id}}</th>
            <td>{{$post->title}}</td>
            <td>{{$post->user->name}}</td>
            <td>{{$post->featured}}</td>
            <td>{{$post->difficulty}}</td>
            <td>{{ $post->reading_time }}</td>
            <td>{{$post->created_at->translatedFormat('d F Y ')}}</td>
            <td>
                @foreach($post->technologies as $technology)
                {{$technology->name}}
                @endforeach
            </td>
            <td><a href="{{route('posts.show',$post)}}" class="btn btn-primary">Show</a></td>
            <td><a href="{{route('posts.show',$post)}}" class="btn btn-warning">Modify</a></td> <!-- MODIFY -->
            <td><a href="{{route('posts.show',$post)}}" class="btn btn-danger">Delete</a></td> <!-- DELETE -->
        </tr>
        @endforeach
    </tbody>

@endsection