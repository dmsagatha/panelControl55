
<tr>
  <td rowspan="2">{{ $user->id }}</td>
  <th scope="row">
    {{ $user->name }} 
    <span class="note">Nombre de Empresa</span>
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
      <a href="{{ route('users.show', $user) }}" class="btn btn-link"><span class="oi oi-eye"></span></a>
      <a href="{{ route('users.edit', $user) }}" class="btn btn-link"><span class="oi oi-pencil"></span></a>
      
      <form method="POST" action="{{ route('users.trash', $user) }}" class="d-inline">
        @csrf
        @method('PATCH')

        <button type="submit" class="btn btn-link"><span class="oi oi-trash"></span></button>
        <button type="submit" class="btn btn-outline-danger btn-sm"><span class="oi oi-trash"></span></button>
      </form>
    @endif
  </td>
</tr>
<tr class="skills">
  <td colspan="1"><span class="note">Profesion aqui</span></td>
  <td colspan="4"><span class="note">Lorem, Ipsum dolor, Sit amet, Consectetur Adipisicing elit</span></td>
</tr>