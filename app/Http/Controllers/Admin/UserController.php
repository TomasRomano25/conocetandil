<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $usuarios = User::latest()->paginate(10);
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.usuarios.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);

        $validated['is_admin'] = $request->boolean('is_admin');
        $validated['password'] = bcrypt($validated['password']);

        User::create($validated);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario creado correctamente.');
    }

    public function edit(User $usuario)
    {
        return view('admin.usuarios.edit', compact('usuario'));
    }

    public function update(Request $request, User $usuario)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($usuario->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'is_admin' => 'boolean',
        ]);

        $validated['is_admin'] = $request->boolean('is_admin');

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = bcrypt($validated['password']);
        }

        $usuario->update($validated);

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario actualizado correctamente.');
    }

    public function destroy(User $usuario)
    {
        if ($usuario->id === auth()->id()) {
            return redirect()->route('admin.usuarios.index')->with('error', 'No podés eliminarte a vos mismo.');
        }

        $usuario->delete();

        return redirect()->route('admin.usuarios.index')->with('success', 'Usuario eliminado correctamente.');
    }

    public function grantPremium(Request $request, User $usuario)
    {
        $request->validate([
            'duration' => 'required|in:1month,3months,6months,1year,custom',
            'expires_at' => 'required_if:duration,custom|nullable|date|after:today',
        ]);

        $expiresAt = match ($request->duration) {
            '1month'   => now()->addMonth(),
            '3months'  => now()->addMonths(3),
            '6months'  => now()->addMonths(6),
            '1year'    => now()->addYear(),
            'custom'   => \Carbon\Carbon::parse($request->expires_at),
        };

        $usuario->update(['premium_expires_at' => $expiresAt]);

        return redirect()->route('admin.usuarios.edit', $usuario)
            ->with('success', "Acceso Premium otorgado hasta {$expiresAt->format('d/m/Y')}.");
    }

    public function revokePremium(User $usuario)
    {
        $usuario->update(['premium_expires_at' => null]);

        return redirect()->route('admin.usuarios.edit', $usuario)
            ->with('success', 'Acceso Premium revocado.');
    }
}
