@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="fs-4 text-secondary my-4">
        {{ __('Dashboard') }}
    </h2>
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">Posts</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <h5>Benvenuto <span class="text-danger bold">{{$user->name}}</span></h5>

                    {{-- {{ __('You are logged in!') }} --}}
                    {{-- create posts --}}
                    <a class="d-block" href="{{route('posts.index')}}"> POSTS</a>

                    <h6 class="pt-3">Last posts</h6>
                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Difficulty</th>
                            <th scope="col">tags</th>
                            <th scope="col">technologies</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                            <tr>
                                <td>{{$post->title}}</td>
                                <td>
                                  @for($i = 0; $i < $post->difficulty; $i++)
                                  <i class="bi bi-star-fill"></i>
                                  @endfor
                                </td>
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
                            </tr>
                            @endforeach
                      </table>
            
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
