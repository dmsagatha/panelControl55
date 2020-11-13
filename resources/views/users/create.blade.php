@extends('layout')

@section('title', 'Crear usuario')

@section('content')
  <h1 class="mt-3">Crear usuario</h1>

  <form method="POST" action="{{ route('users.store') }}">
    @csrf

    <button type="submit">Crear usuario</button>
  </form>
  <hr>
  
  <p>
    <a href="{{ route('users.index') }}">Regresar al listado de usuarios</a>
  </p>
@endsection