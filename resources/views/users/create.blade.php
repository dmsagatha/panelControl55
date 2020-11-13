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
        {!! $errors->first('name', '<div class="text-danger">:message</div>') !!}
      </div>
      <div class="form-group col">
        <label for="email">Correo electrónico:</label>
        <input class="form-control" type="email" name="email" id="email">
        {!! $errors->first('email', '<div class="text-danger">:message</div>') !!}
      </div>
    </div>

    <div class="form-row">
      <div class="form-group col">
        <label for="password">Contraseña:</label>
        <input class="form-control" type="password" name="password" id="password" placeholder="Mayor a 6 caracteres">
        {!! $errors->first('password', '<div class="text-danger">:message</div>') !!}
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