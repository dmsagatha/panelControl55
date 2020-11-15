@extends('layout')

@section('title', 'Editar usuario')

@section('content')
<div class="card">
  <div class="card-body">
    <h1 class="card-title">Editar usuario</h1>
      
    @include('shared._errors')

    <form method="POST" action="{{ route('users.update', $user) }}">
      @csrf @method('PUT')

      @include('users._fields')
      
      <div class="card-header white white-text text-center btn-group-xs">
        <button type="submit" class="btn btn-success text-center">Actualizar usuario</button>
        <a href="{{ route('users.index') }}" class="card-link">
          Regresar al listado de usuarios
        </a>
      </div>
    </form>
@endsection