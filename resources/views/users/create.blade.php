@extends('layout')

@section('title', 'Crear usuario')

@section('content')
  @component('shared._card')
    @slot('header', 'Crear usuario')
    
    @include('shared._errors')

    <form method="POST" action="{{ route('users.store') }}">
      @include('users._fields')
      {{-- 2-14-View Components y creación de directivas personalizadas para Laravel y Blade	 --}}
      {{-- {{ new App\Http\ViewComponents\UserFields($user) }}} --}}

      {{-- @render('UserFields', ['user' => $user]) --}}
      
      <div class="card-header white white-text text-center btn-group-xs">
        <button type="submit" class="btn btn-primary text-center">Crear usuario</button>
        <a href="{{ route('users.index') }}" class="card-link">
          Regresar al listado de usuarios
        </a>
      </div>
    </form>
  @endcomponent
@endsection