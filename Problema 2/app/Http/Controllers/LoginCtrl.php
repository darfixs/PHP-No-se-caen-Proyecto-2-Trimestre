<?php

/**
 * Controlador de login / logout.
 *
 * Usa el Auth nativo de Laravel (en lugar de $_SESSION a pelo).
 * El modelo "User" que Laravel usa es App\Models\Empleado.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginCtrl extends Controller
{
    /** Muestra el formulario de login. */
    public function formulario()
    {
        return view('auth.login');
    }

    /**
     * Procesa el envío del formulario.
     * Si las credenciales son correctas, lo redirijo a /tareas.
     */
    public function login(Request $request)
    {
        // Validación básica
        $datos = $request->validate([
            'correo' => ['required', 'email'],
            'password' => ['required', 'string'],
        ], [
            'correo.required'   => 'Introduzca el correo.',
            'correo.email'      => 'El correo no es válido.',
            'password.required' => 'Introduzca la contraseña.',
        ]);

        // "remember" → recordarme
        $recordar = (bool) $request->input('recordarme', false);

        // Intento hacer login. Laravel se encarga de comparar la password hasheada.
        if (Auth::attempt($datos, $recordar)) {
            $request->session()->regenerate(); // seguridad: regenero el id de sesión
            return redirect()->intended('/tareas');
        }

        // Si falla, vuelvo al formulario con un error
        return back()->withErrors([
            'correo' => 'Credenciales incorrectas.',
        ])->onlyInput('correo');
    }

    /** Cierra sesión y redirige al login. */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
