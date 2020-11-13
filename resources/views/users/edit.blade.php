@extends('layout')

@section('title', 'Editar usuario')

@section('content')
<div class="card">
  <div class="card-body">
    <h1 class="card-title">Editar usuario</h1>
    @if ($errors->any())
      <div class="alert alert-danger">
        <h6>Por favor corregir los errores:</h6>
        <ul>
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('users.update', $user) }}">
      @csrf @method('PUT')

      <div class="form-row">
        <div class="form-group col-4">
          <label for="name">Nombre:</label>
          <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $user->name) }}">
          @if ($errors->has('name'))
            <div class="text-danger">{{ $errors->first('name') }}</div>
          @endif
        </div>
        <div class="form-group col">
          <label for="email">Correo electrónico:</label>
          <input class="form-control" type="email" name="email" id="email" value="{{ old('email', $user->email) }}">
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
        <button type="submit" class="btn btn-success text-center">Actualizar usuario</button>
        <a href="{{ route('users.index') }}" class="card-link">
          Regresar al listado de usuarios
        </a>
      </div>
    </form>
@endsection