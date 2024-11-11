<div class="table-container">
    <table id="usersTable" class="display styled-table" style="width:100%">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Status</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id_user }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if ($user->id_role != 1) 
                            <form method="POST" action="{{ route('admin.user.status.update', $user->id_user) }}">
                                @csrf
                                <select name="status" onchange="this.form.submit()">
                                    <option value="1" {{ $user->status ? 'selected' : '' }}>Activo</option>
                                    <option value="0" {{ !$user->status ? 'selected' : '' }}>Inactivo</option>
                                </select>
                            </form>
                        @endif
                    </td>
                    <td>
                        @if ($user->id_role != 1) 
                            <form action="{{ route('admin.user.delete', $user->id_user) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-button">Eliminar</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
