@extends('layouts.app')

@section('content')

<h1 class="text-center">{{$post->title}}</h1>

<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">title</th>
            <th scope="col">read time</th>
            <th scope="col">featured</th>
            <th scope="col">tags</th>
            <th scope="col">created</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row">{{$post->id}}</th>
            <td>{{$post->title}}</td>
            <td>{{ $post->reading_time }}</td>
            <td>
                @if($post->featured == 1)
                True
                @elseif($post->featured == 0)
                False
                @endif
            </td>
            <td>
                @foreach($post->tags as $tag)
                {{$tag->name}}
                @endforeach
            </td>
            <td>{{ucfirst($post->created_at->translatedFormat('M d, Y'))}}</td>
        </tr>
    </tbody>
</table>
@foreach($post->images as $image)
<div class="col-md-4">
    @if($image->is_featured === 1)
    <h5 class="text-center">Copertina</h5>
    @endif
    <img src="{{ asset('storage/' . $image->path) }}" alt="{{ $image->alt }}" class="img-fluid">
</div>
@endforeach

{{-- CONTENT --}}

@endsection
