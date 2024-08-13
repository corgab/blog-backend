@extends('layouts.app')
@section('content')

<h1 class="text-center">TECHNOLOGIES</h1>

<a href="{{route('technologies.create')}}" class="btn btn-primary">Create</a>
<table class="table">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">name</th>
        <th scope="col">actions</th>
      </tr>
    </thead>
    <tbody>
    @foreach($technologies as $technology)
      <tr>
        <th scope="row">{{$technology->id}}</th>
        <td>{{$technology->name}}</td>
        <td>
            <form action="{{route('technologies.destroy', $technology)}}" method="POST">
            @csrf
            @method('DELETE')
            <button class="btn btn-danger">Delete</button>

            </form>
        </td>
      </tr>
      
    @endforeach
    </tbody>
  </table>

@endsection