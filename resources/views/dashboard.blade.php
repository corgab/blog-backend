@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="fs-4 text-secondary my-4">
        {{ __('Dashboard') }}
    </h2>
    <div class="row justify-content-center">
        <div class="col">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <h5>Benvenuto <span class="text-danger text-uppercase bold">{{$user->name}}</span></h5>

                    {{-- {{ __('You are logged in!') }} --}}
                    {{-- create posts --}}
                    @if(isset($posts))
                    <div class="d-flex column-gap-3">
                        <a href="{{route('posts.index')}}"> POSTS</a>
                        <a href="{{route('tags.index')}}">TAGS</a>
                        @if (Auth::user()->can('view drafts'))
                        <a href="{{ route('posts.drafts') }}">Bozze</a>
                    @endif
                    </div>
                    <h6 class="pt-3">Last posts</h6>
                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Title</th>
                            <th scope="col">tags</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                            <tr>
                                <td>{{$post->title}}</td>
                                <td>
                                  @foreach($post->tags as $tag)
                                  {{$tag->name}}
                                  @endforeach
                              </td>
                            </tr>
                            @endforeach
                      </table>
                      @else
                      <h6>Create your first <a href="{{route('posts.create')}}">post</a></h6>
                      @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
