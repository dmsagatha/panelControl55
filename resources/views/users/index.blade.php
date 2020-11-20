@extends('layout')

@section('title', 'Usuarios')

@section('content')
  <div class="d-flex justify-content-between align-items-end mb-3">
    <h1 class="pb-1">{{ $title }}</h1>

    <P>
      <a href="{{ route('users.trashed') }}" class="btn btn-outline-dark">Ver papelera</a>
      <a href="{{ route('users.create') }}" class="btn btn-dark">Crear usuario</a>
    </P>
  </div>

  @include('users._filters')
  
  @if ($users->isNotEmpty()) 

    <p>Viendo página {{ $users->currentPage() }} de {{ $users->lastPage() }}</p>

    <div class="table-responsive-lg">
      <table class="table table-sm">
        <thead class="thead-dark text-center">
          <tr>
            <th scope="col"># <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>
            <th scope="col" class="sort-desc">Nombre <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>
            <th scope="col">Empresa <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>
            <th scope="col">Correo <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>
            <th scope="col">Rol <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th>
            {{-- <th scope="col">Fechas <span class="oi oi-caret-bottom"></span><span class="oi oi-caret-top"></span></th> --}}
            <th scope="col" class="th-actions">Acciones</th>
          </tr>
        </thead>
        <tbody>
          {{-- @foreach ($users as $user)
            @include('users._row', ['user' => $user])
          @endforeach --}}

          @each('users._row', $users, 'user')
        </tbody>
      </table>
  
      {{-- {{ $users->render() }} --}}
      {{-- {{ $users->links('shared.simple-pagination') }} --}}
      {{-- {{ $users->links() }} --}}
      {{-- {{ $users->appends(request(['search']))->links() }} --}}
      {{ $users->links() }}
    </div>
  @else
    <p>No hay usuarios registrados.</p>
  @endif
@endsection