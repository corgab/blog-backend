@extends('layouts.app')
@section('content')

<h1 class="text-center">POSTS</h1>

@if(isset($posts))
<a href="{{route('posts.create')}}" class="btn btn-primary">Create</a>
<!--  turn back -->
<table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Title</th>
        <th scope="col">tags</th>
        @can('approve posts')
        <th scope="col">Status</th>
        @endcan
        <th scope="col" colspan="3">actions</th>
      </tr>
    </thead>
    <tbody>
        @foreach($posts as $post)
        <tr>

            <th scope="row">{{$post->id}}</th>
            <td>{{$post->title}}</td>
            <td>
              @foreach($post->tags as $tag)
              {{$tag->name}}
              @endforeach
            </td>
            @can('approve posts')
            <td>
                @if ($post->status === 'draft')
                    Bozza
                @else
                    Pubblicato
                @endif
            </td>
            @endcan
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
        
    @else
        
      <h1 class="text-center">Non hai ancora creato un post</h1>
    
    @endif
@endsection