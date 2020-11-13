@extends('layout')

@section('title', 'Usuarios')

@section('content')
  <h1 class="mt-3">{{ $title }}</h1>

  <ul>
    @forelse ($users as $user)
    <li>
      {{ $user->id }} - {{ $user->name }} ({{ $user->email }})
      <a href="{{ route('users.show', $user->id) }}">Ver detalles - (route)</a>
    </li>
    @empty
      <li>No hay usuarios registrados.</li>
    @endforelse
  </ul>
@endsection