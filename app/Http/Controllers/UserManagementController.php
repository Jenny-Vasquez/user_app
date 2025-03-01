<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // Solo podran acceder los usuarios que hayan sido logeados
    }

    /**
     * Mostramos la lista de usuarios.
     */
    public function index()
    {
        $users = User::paginate(10); // Paginación en lugar de traer todos los usuarios
        return view('users.index', compact('users'));
    }

    /**
     * Mostramos el formulario para crear un nuevo usuario (solo administradores).
     */
    public function create()
    {

        // Permite que tanto superadmin como admin puedan crear usuarios
        if (Auth::user()->role !== 'superadmin' && Auth::user()->role !== 'admin') {
            return redirect()->route('users.index')->with('error', 'No tienes permiso para crear usuarios.');
        }
    
        return view('users.create');
    }
    

    /**
     * Guarda un nuevo usuario (solo administradores).
     */
    public function store(Request $request)
    {
        // Solo admin puede crear usuarios
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->route('users.index')->with('error', 'No tienes permiso para crear usuarios.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'in:user,admin'],
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('status', 'Usuario creado correctamente');
    }

    /**
     * Muestra el formulario de edición (admin puede editar a todos, usuario solo su perfil).
     */
    
    public function edit(User $user)
    {
        // Solo superadmin o admin pueden editar cualquier usuario, usuario solo su propio perfil
        if (Auth::user()->role !== 'superadmin' && Auth::user()->role !== 'admin' && Auth::user()->id !== $user->id) {
            return redirect()->route('users.index')->with('error', 'No tienes permiso para editar este usuario.');
        }
    
        return view('users.edit', compact('user'));
    }
    

    /**
     * Actualiza la información del usuario.
     */
    public function update(Request $request, User $user)
    {
        // Si el usuario es un superadmin, puede editar todos los perfiles excepto el suyo propio
        if (Auth::user()->role === 'superadmin') {
            if (Auth::user()->id === $user->id) {
                return redirect()->route('users.index')->with('error', 'No puedes editar tu propio perfil.');
            }
            // Validación para superadmin (puede actualizar cualquier perfil)
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                'role' => ['required', 'in:user,admin'],
            ]);
            $user->update($request->only(['name', 'email', 'role']));
            return redirect()->route('users.index')->with('status', 'Perfil actualizado correctamente');
        }
    
        // Si el usuario es un admin, puede editar su propio perfil y el de los usuarios normales
        if (Auth::user()->role === 'admin') {
            if (Auth::user()->id === $user->id) {
                // El admin no puede editar su propio rol, solo nombre y correo
                $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                ]);
                $user->update($request->only(['name', 'email']));
                return redirect()->route('users.index')->with('status', 'Perfil actualizado correctamente');
            }
    
            if ($user->role === 'user') {
                // El admin puede actualizar el perfil de los usuarios normales
                $request->validate([
                    'name' => ['required', 'string', 'max:255'],
                    'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                ]);
                $user->update($request->only(['name', 'email']));
                return redirect()->route('users.index')->with('status', 'Perfil de usuario actualizado correctamente');
            }
    
            // Los admins no pueden actualizar el perfil de otros admins
            return redirect()->route('users.index')->with('error', 'No tienes permiso para actualizar este perfil.');
        }
    
        // Los usuarios solo pueden actualizar su propio perfil
        if (Auth::user()->id === $user->id) {
            // Los usuarios solo pueden actualizar su nombre y correo
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            ]);
            $user->update($request->only(['name', 'email']));
            return redirect()->route('users.index')->with('status', 'Perfil actualizado correctamente');
        }
    
        return redirect()->route('users.index')->with('error', 'No tienes permiso para actualizar este perfil.');
    }
    
    

    /**
     * Elimina un usuario (solo admin, y no puede eliminarse a sí mismo ni al usuario ID 1).
     */
   public function destroy(User $user)
{
    if (auth()->user()->id === $user->id || $user->id === 1) {
        return redirect()->route('users.index')->with('error', 'No tienes permisos para eliminar este usuario.');
    }

    $user->delete();
    return redirect()->route('users.index')->with('status', 'Usuario eliminado correctamente.');
}
}
