@extends('layouts.app')
@section('content')

<h1 class="text-center">POSTS</h1>

<table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">nome</th>
        <th scope="col">slug</th>
        <th scope="col">read time</th>
        <th scope="col">difficulty</th>
        <th scope="col">featured</th>
        <th scope="col">actions</th>
      </tr>
    </thead>
    <tbody>
        @foreach($posts as $post)
        <tr>

            <th scope="row">{{$post->id}}</th>
            <td>{{$post->name}}</td>
            <td>{{$post->slug}}</td>
            <td>{{$post->read_time}}</td>
            <td>{{$post->difficulty}}</td>
            <td>{{$post->featured}}</td>
            {{-- <td>{{$post->created_at}}</td> --}}
            <td><a href="{{route('posts.show',$post)}}" class="btn btn-primary">Show</a></td>
        </tr>
        @endforeach
    </tbody>

@endsection