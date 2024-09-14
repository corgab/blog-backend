@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="fs-4 text-secondary my-4">{{ __('Dashboard') }}</h2>

    <div class="row justify-content-center my-5">
        <div class="col-lg-8">
            <div class="card">
                {{-- <div class="card-header">Dashboard</div> --}}
                <div class="card-body">
                    @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                    @endif

                    <h5>Benvenuto <span class="text-danger text-uppercase fw-bold">{{ Auth::user()->name }}</span></h5>

                    <div class="d-flex gap-3 mb-3">
                        <a href="{{ route('posts.index') }}" class="btn btn-primary">Posts</a>
                        @can('manage tags')
                        <a href="{{ route('tags.index') }}" class="btn btn-secondary">Tags</a>
                        @endcan
                        @can('approve posts')
                        <a href="{{ route('posts.drafts') }}" class="btn btn-warning">Bozze</a>
                        @endcan 
                    </div>
                    
                    @if(isset($posts) && $posts->isNotEmpty())
                    <h6 class="pt-3">Ultimi Post</h6>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Title</th>
                                <th scope="col">Tags</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $post)
                            <tr>
                                <td>{{ $post->title }}</td>
                                <td>
                                    @foreach($post->tags as $tag)
                                    <span class="badge text-bg-secondary">{{ $tag->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <span>{{ ucfirst($post->status) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <h6 class="text-center">Crea il tuo primo post <a href="{{ route('posts.create') }}" class="btn btn-primary">post</a></h6>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
