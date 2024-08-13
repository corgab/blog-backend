@extends('layouts.app')
@section('content')

<h1 class="text-center">POSTS</h1>

@if(isset($posts))
<table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Title</th>
        {{-- <th scope="col">Creator</th> --}}
        {{-- <th scope="col">Featured</th> --}}
        <th scope="col">Difficulty</th>
        {{-- <th scope="col">read time</th> --}}
        {{-- <th scope="col">created</th> --}}
        <th scope="col">technologies</th>
        <th scope="col">tags</th>
        <th scope="col" colspan="3">actions</th>
      </tr>
    </thead>
    <tbody>
        @foreach($posts as $post)
        <tr>

            <th scope="row">{{$post->id}}</th>
            <td>{{$post->title}}</td>
            {{-- <td>{{$post->user->name}}</td> --}}
            {{-- <td>{{$post->featured}}</td> --}}
            {{-- <td>
              @if($post->featured == 1)
              True
              @elseif($post->featured == 0)
              False
              @endif
            </td> --}}
            <td>
              @for($i = 0; $i < $post->difficulty; $i++)
              <i class="bi bi-star-fill"></i>
              @endfor
            </td>
            {{-- <td>{{ $post->reading_time }}</td> --}}
            {{-- <td>{{$post->created_at->translatedFormat('d F Y ')}}</td> --}}
            <td>
                @foreach($post->technologies as $technology)
                {{$technology->name}}
                @endforeach
            </td>
            <td>
              @foreach($post->tags as $tag)
              {{$tag->name}}
              @endforeach
          </td>
            <td><a href="{{route('posts.show',$post)}}" class="btn btn-primary">Show</a></td>
            <td><a href="{{route('posts.edit', $post)}}" class="btn btn-primary">Modify</a></td> <!-- MODIFY -->
            <td>
              <form action="{{route('posts.destroy', $post)}}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
    
              </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

<a href="{{route('posts.create')}}" class="btn btn-primary">Create</a>
        
    @else
        
      <h1 class="text-center">Non hai ancora creato un post</h1>
    
    @endif
@endsection