@extends('layout')

@section('title', 'Crear usuario')

@section('content')
  <div class="card">
    <div class="card-body">
      <h1 class="card-title">Crear usuario</h1>
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

      <form method="POST" action="{{ route('users.store') }}">
        @csrf
    
        <div class="form-row">
          <div class="form-group col-4">
            <label for="name">Nombre:</label>
            <input class="form-control" type="text" name="name" id="name" value="{{ old('name') }}">
            @if ($errors->has('name'))
              <div class="text-danger">{{ $errors->first('name') }}</div>
            @endif
          </div>
          <div class="form-group col">
            <label for="email">Correo electrónico:</label>
            <input class="form-control" type="email" name="email" id="email" value="{{ old('email') }}">
            {!! $errors->first('email', '<div class="text-danger">:message</div>') !!}
          </div>
        </div>
    
        <div class="form-row">
          <div class="form-group col">
            <label for="password">Contraseña:</label>
            <input class="form-control" type="password" name="password" id="password" placeholder="Mayor a 6 caracteres">
            {!! $errors->first('password', '<div class="text-danger">:message</div>') !!}
          </div>
          <div class="form-group col">
            <label for="bio">Biografía:</label>
            <input class="form-control" type="text" name="bio" id="bio">
            {!! $errors->first('bio', '<div class="text-danger">:message</div>') !!}
          </div>
        </div>
    
        <div class="form-row">
          <div class="form-group col">
            <label for="profession_">Profesión:</label>
            <select name="profession_id" id="profession_id" class="form-control">
              <option value="">Seleccionar</option>
              {{-- @foreach (App\Models\Profession::orderBy('title')->get() as $profession) --}}
              @foreach ($professions as $profession)
                <option value="{{ $profession->id }}"{{ old('profession_id') == $profession->id ? ' selected' : '' }}>
                  {{ $profession->title }}
                </option>
              @endforeach
            </select>
            {!! $errors->first('profession_', '<div class="text-danger">:message</div>') !!}
          </div>
          <div class="form-group col">
            <label for="twitter">Twitter:</label>
            <input class="form-control" type="text" name="twitter" id="twitter" placeholder="https://twitter.com/Stydenet">
            {!! $errors->first('twitter', '<div class="text-danger">:message</div>') !!}
          </div>
        </div>
        
        <div class="card-header white white-text text-center btn-group-xs">
          <button type="submit" class="btn btn-primary text-center">Crear usuario</button>
          <a href="{{ route('users.index') }}" class="card-link">
            Regresar al listado de usuarios
          </a>
        </div>
      </form>      
    </div>
  </div>
@endsection