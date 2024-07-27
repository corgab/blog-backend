@extends('layouts.app')

@section('content')

<h1 class="text-center">{{$post->title}}</h1>

<table class="table">
    <thead>
        <tr>
            <th scope="col">#</th>
            {{-- <th scope="col">nome</th> --}}
            <th scope="col">content</th>
            <th scope="col">creator</th>
            <th scope="col">read time</th>
            <th scope="col">featured</th>
            <th scope="col">difficulty</th>
            <th scope="col">created</th>
            <th scope="col">tags</th>
            <th scope="col">technologies</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th scope="row">{{$post->id}}</th>
            {{-- <td>{{$post->title}}</td> --}}
            <td>{{$post->content}}</td>
            <td>{{$post->user->name}}</td>
            <td>{{ $post->reading_time }}</td>
            <td>
                @if($post->featured == 1)
                True
                @elseif($post->featured == 0)
                False
                @endif
              </td>
              <td>
                @for($i = 0; $i < $post->difficulty; $i++)
                <i class="bi bi-star-fill"></i>
                @endfor
              </td>
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
