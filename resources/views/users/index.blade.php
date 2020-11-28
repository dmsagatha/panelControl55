@extends('layout')

@section('title', 'Usuarios')

@section('content')
  <div class="d-flex justify-content-between align-items-end mb-3">
    <h1 class="pb-1">{{ trans("users.title.{$view}") }}</h1>

    <P>
      @if ($view == 'index')
        <a href="{{ route('users.trashed') }}" class="btn btn-outline-dark">Ver papelera</a>
        <a href="{{ route('users.create') }}" class="btn btn-dark">Crear usuario</a>
      @else
        <a href="{{ route('users.index') }}" class="btn btn-outline-dark">Regresar al listado de usuarios</a>
      @endif      
    </P>
  </div>

  @includeWhen($view == 'index', 'users._filters')
  
  @if ($users->isNotEmpty()) 
    <div class="table-responsive-lg">
      <table class="table table-sm">
        <thead class="thead-dark text-center">
          <tr>
            <th scope="col"># <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>
            <th scope="col" class="sort-desc">Nombre <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>
            <th scope="col">Correo electrónico <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>
            <th scope="col">Fechas <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>
            <th scope="col" class="th-actions">Acciones</th>
          </tr>
        </thead>
        <tbody>

          @each('users._row', $users, 'user')

        </tbody>
      </table>
      
      {{ $users->links() }}
    </div>
  @else
    <p>No hay usuarios registrados.</p>
  @endif
@endsection