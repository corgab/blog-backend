@extends('layouts.app')
@section('content')
<table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">name</th>
        <th scope="col">actions</th>
      </tr>
    </thead>
    <tbody>
    @foreach($tags as $tag)
      <tr>
        <th scope="row">{{$tag->id}}</th>
        <td>{{$tag->name}}</td>
        <td>
          <form action="{{route('tags.destroy', $tag)}}" method="POST">
          @csrf
          @method('DELETE')
          <button class="btn btn-danger" type="submit">Delete</button>

          </form>
      </td>
      </tr>
    @endforeach
    </tbody>
  </table>

  <a href="{{route('tags.create')}}" class="btn btn-primary">Create</a>
@endsection