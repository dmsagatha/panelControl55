@extends('layout')

@section('title', 'Crear usuario')

@section('content')
  <x-card>
    @slot('header', 'Crear usuario')
    
    @include('shared._errors')

    <form method="POST" action="{{ route('users.store') }}">
      @include('users._fields')
      
      <div class="card-header white white-text text-center btn-group-xs">
        <button type="submit" class="btn btn-primary text-center">Crear usuario</button>
        <a href="{{ route('users.index') }}" class="card-link">
          Regresar al listado de usuarios
        </a>
      </div>
    </form>
  </x-card>
@endsection