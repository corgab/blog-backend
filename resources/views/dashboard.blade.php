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

                    <h5>Benvenuto <span class="text-danger text-uppercase bold">{{ Auth::user()->name }}</span></h5>

                    {{-- {{ __('You are logged in!') }} --}}
                    
                    <div class="d-flex column-gap-3">
                        <a href="{{ route('posts.index') }}">POSTS</a>
                        {{-- Mostra il link ai tag solo se l'utente ha il permesso --}}
                        @can('manage tags')
                        <a href="{{ route('tags.index') }}">TAGS</a>
                        @endcan
                        @can('approve posts')
                        <a href="{{ route('posts.drafts') }}">BOZZE</a>
                        @endcan 
                    </div>
                    
                    @if(isset($posts))
                    <h6 class="pt-3">Last posts</h6>
                    <table class="table">
                        <thead>
                          <tr>
                            <th scope="col">Title</th>
                            <th scope="col">Tags</th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                            <tr>
                                <td>{{ $post->title }}</td>
                                <td>
                                  @foreach($post->tags as $tag)
                                  {{ $tag->name }}
                                  @endforeach
                              </td>
                            </tr>
                            @endforeach
                      </table>
                      @else
                      <h6>Create your first <a href="{{ route('posts.create') }}">post</a></h6>
                      @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
