@extends('layout')

@section('title', 'Usuarios')

@section('content')
  <h1 class="mt-3">{{ $title }}</h1>

  <P>
    <a href="{{ route('users.create') }}">Crear usuario</a>
  </P>

  <ul>
    @forelse ($users as $user)
    <li>
      {{ $user->id }} - {{ $user->name }} ({{ $user->email }})
      <a href="{{ route('users.show', $user) }}">Ver detalles - (route)</a> | 
      <a href="{{ route('users.edit', $user) }}">Editar</a> | 

      <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline">
        @csrf @method('DELETE')

        <button type="submit">Eliminar</button>
      </form>
    </li>
    @empty
      <li>No hay usuarios registrados.</li>
    @endforelse
  </ul>
@endsection