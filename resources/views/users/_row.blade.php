<tr>
  <td rowspan="2">{{ $user->id }}</td>
  <th scope="row">
    {{ $user->name }} 
    <span class="note">Habilidades:</span>
  </th>
  <td>{{ $user->email }}</td>
  <td>{{ $user->role }}</td>
  <td>
    <span class="note">Registro: {{ $user->created_at->format('d/m/Y') }}</span>
    <span class="note">Último login: {{ $user->created_at->format('d/m/Y') }}</span>
  </td>
  <td class="text-right">
    @if ($user->trashed())
    <form method="POST" action="{{ route('users.destroy', $user) }}" class="d-inline">
      @csrf
      @method('DELETE')

      <button type="submit" class="btn btn-link"><span class="oi oi-circle-x"></span></button>
    </form>
    @else
      <a href="{{ route('users.show', $user) }}" class="btn btn-outline-info btn-sm"><span class="oi oi-eye"></span></a>
      <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-dark btn-sm"><span class="oi oi-pencil"></span></a>
      
      <form method="POST" action="{{ route('users.trash', $user) }}" class="d-inline">
        @csrf
        @method('PATCH')
        
        <button type="submit" class="btn btn-outline-danger btn-sm"><span class="oi oi-trash"></span></button>
      </form>
    @endif
  </td>
</tr>
<tr class="skills">
  <td colspan="1">
    {{-- Opción 1 --}}
    {{-- @if ($user->profile->profession)
      <span class="note">{{ $user->profile->profession->title }}</span>
    @endif --}}
    {{-- Opción 2 --}}
    {{-- <span class="note">{{ optional($user->profile->profession)->title }}</span> --}}
    {{-- Opción 3 - withDefault() --}}
    <span class="note">{{ $user->profile->profession->title }}</span>
  </td>
  <td colspan="4">
    <span class="note">
      {{ $user->skills->implode('name', ', ') ?: 'Sin habilidades :(' }}
    </span>
  </td>
</tr>