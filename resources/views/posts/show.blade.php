@extends('layouts.app')

@section('content')

<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">nome</th>
            <th scope="col">content</th>
            <th scope="col">read time</th>
            <th scope="col">difficulty</th>
            <th scope="col">featured</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row">{{$post->id}}</th>
            <td>{{$post->name}}</td>
            <td>{{$post->slug}}</td>
            <td>{{$post->read_time}}</td>
            <td>{{$post->difficulty}}</td>
            <td>{{$post->featured}}</td>
            <td>{{$post->created_at}}</td>
        </tr>
    </tbody>
</table>

@endsection