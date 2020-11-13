@extends('layout')

@section('title', 'Crear usuario')

@section('content')
  <h1 class="mt-3">Crear usuario</h1>

  <form method="POST" action="{{ route('users.store') }}">
    @csrf

    <div class="form-row">
      <div class="form-group col-4">
        <label for="name">Nombre:</label>
        <input class="form-control" type="text" name="name" id="name">
      </div>
      <div class="form-group col">
        <label for="email">Correo electrónico:</label>
        <input class="form-control" type="email" name="email" id="email">
      </div>
    </div>

    <div class="form-row">
      <div class="form-group col">
        <label for="password">Contraseña:</label>
        <input class="form-control" type="password" name="password" id="password" placeholder="Mayor de 6 caracteres">
      </div>
    </div>
    
    <div class="card-header white white-text text-center btn-group-xs">
      <button type="submit" class="btn btn-info text-center">Crear usuario</button>
    </div>
  </form>

  <hr>
  
  <p>
    <a href="{{ route('users.index') }}">Regresar al listado de usuarios</a>
  </p>
@endsection