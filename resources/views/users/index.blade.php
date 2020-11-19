@extends('layout')

@section('title', 'Usuarios')

@section('content')
  <div class="d-flex justify-content-between align-items-end mb-2 mt-4">

    <h1 class="pb-1">{{ $title }}</h1>

    <P>
      <a href="{{ route('users.trashed') }}" class="btn btn-outline-dark">Ver papelera</a>
      <a href="{{ route('users.create') }}" class="btn btn-dark">Crear usuario</a>
    </P>
  </div>

  @if ($users->isNotEmpty())
    <table class="table table-hover">
      <thead class="thead-dark">
        <tr>
          <th scope="col">#</th>
          <th scope="col">Nombre</th>
          <th scope="col">Correo Electrónico</th>
          <th scope="col">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @foreach ($users as $user)
          <tr>
            <th scope="row">{{ $user->id }}</th>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
              @if ($user->trashed())                
                <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline">
                  @csrf
                  @method('DELETE')

                  <button type="submit" class="btn btn-link"><span class="oi oi-circle-x"></span></button>
                </form>
              @else
                <a href="{{ route('users.show', $user) }}" class="btn btn-link"><span class="oi oi-eye"></span></a>
                <a href="{{ route('users.edit', $user) }}" class="btn btn-link"><span class="oi oi-pencil"></span></a>
                
                <form method="POST" action="{{ route('users.trash', $user) }}" class="d-inline">
                  @csrf
                  @method('PATCH')

                  <button type="submit" class="btn btn-link"><span class="oi oi-trash"></span></button>
                </form>
              @endif
              {{-- <a href="{{ route('users.show', $user) }}" class="btn btn-link"><span class="oi oi-eye"></span></a>
              <a href="{{ route('users.edit', $user) }}" class="btn btn-link"><span class="oi oi-pencil"></span></a>
              <form method="POST" action="{{ route('users.trash', $user) }}" class="d-inline">
                @csrf
                @method('PATCH')

                <button type="submit" class="btn btn-link"><span class="oi oi-trash"></span></button>
              </form> --}}
            </td>
          </tr>            
        @endforeach
      </tbody>
    </table>      
  @else
    <p>No hay usuarios registrados.</p>
  @endif
@endsection