<x-app-layout>

  <x-slot name="title">Usuarios {$user->name}</x-slot>

  <div class="card">
    <div class="card-body">
      <h1 class="mt-3">Usuario #{{ $user->id }}</h1>
    
      <p>Nombre del usuario: {{ $user->name }}</p>
      <p>Correo electrónico: {{ $user->email }}</p>

      <p>
        <a href="{{ route('users.index') }}">Regresar al listado de usuarios</a>
      </p>
    </div>
  </div>
</x-app-layout>