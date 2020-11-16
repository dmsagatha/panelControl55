@extends('layout')

@section('title', 'Editar usuario')

@section('content')
  @component('shared._card')
    @slot('header', 'Editar usuario')
    
    @include('shared._errors')

    <form method="POST" action="{{ route('users.update', $user) }}">
      @csrf @method('PUT')
      @include('users._fields')
      
      {{-- 2-14-View Components y creación de directivas personalizadas para Laravel y Blade	 --}}
      {{-- @render('UserFields', ['user' => $user]) --}}
      
      <div class="card-header white white-text text-center btn-group-xs">
        <button type="submit" class="btn btn-success text-center">Actualizar usuario</button>
        <a href="{{ route('users.index') }}" class="card-link">
          Regresar al listado de usuarios
        </a>
      </div>
    </form>
  @endcomponent
@endsection