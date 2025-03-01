@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <!-- Cabecera con título y botón para crear usuario (solo admins) -->
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ __('User List') }}</span>
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin')
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">New User</a>
                    @endif
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Verificar si hay usuarios -->
                    @if ($users->count() > 0)
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Verification</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ ucfirst($user->role) }}</td>
                                        <td>
                                            @if($user->email_verified_at)
                                                <span class="text-success">OK</span>
                                            @else
                                                <span class="text-danger">NO</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <!-- Botón de Editar (admin o superadmin puede editar a todos, usuario solo a sí mismo) -->
                                                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin' || auth()->user()->id === $user->id)
                                                <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">Edit</a>
                                                &nbsp;&nbsp;  <!-- Agregar espacios entre los botones -->
                                                @endif
                                        
                                                <!-- Botón de Eliminar (solo admin o superadmin, y no puede eliminarse a sí mismo ni al usuario 1) -->
                                                @if((auth()->user()->role === 'admin' || auth()->user()->role === 'superadmin') && auth()->user()->id !== $user->id && $user->id !== 1)
                                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro?')">
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                        
                                        
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $users->links() }}
                        </div>
                    @else
                        <p class="text-center">No hay usuarios registrados.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
