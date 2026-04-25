<?php

/**
 * Validación del formulario de alta/edición de empleados.
 *
 * Se ejecuta automáticamente antes de llegar al controlador.
 * Si hay errores, Laravel redirige al formulario con los mensajes.
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmpleadoRequest extends FormRequest
{
    /** Autorización: solo admins pueden gestionar empleados. */
    public function authorize(): bool
    {
        // Ya protejo la ruta con el middleware SoloAdmin,
        // pero por seguridad lo valido también aquí.
        $usuario = $this->user();
        return $usuario && $usuario->esAdmin();
    }

    /** Reglas de validación. */
    public function rules(): array
    {
        $id = $this->route('empleado'); // id del empleado que se edita (si aplica)

        return [
            'dni'        => ['required', 'string', 'max:20', Rule::unique('empleados', 'dni')->ignore($id)],
            'nombre'     => ['required', 'string', 'max:120'],
            'correo'     => ['required', 'email', 'max:150', Rule::unique('empleados', 'correo')->ignore($id)],
            'telefono'   => ['nullable', 'regex:/^[0-9\s\-]{6,20}$/'],
            'direccion'  => ['nullable', 'string', 'max:200'],
            'fecha_alta' => ['required', 'date'],
            'tipo'       => ['required', Rule::in(['Administrador', 'Operario'])],
            // En alta la password es obligatoria, en edición si viene vacía se deja la antigua
            'password'   => [$id ? 'nullable' : 'required', 'nullable', 'string', 'min:6'],
        ];
    }

    /** Mensajes personalizados (en español). */
    public function messages(): array
    {
        return [
            'dni.required'      => 'El DNI es obligatorio.',
            'dni.unique'        => 'Ya existe un empleado con ese DNI.',
            'nombre.required'   => 'El nombre es obligatorio.',
            'correo.required'   => 'El correo es obligatorio.',
            'correo.email'      => 'El correo no tiene un formato válido.',
            'correo.unique'     => 'Ya existe un empleado con ese correo.',
            'telefono.regex'    => 'El teléfono no tiene un formato válido.',
            'fecha_alta.required' => 'La fecha de alta es obligatoria.',
            'tipo.required'     => 'Debe seleccionar el tipo.',
            'password.required' => 'La contraseña es obligatoria en el alta.',
            'password.min'      => 'La contraseña debe tener al menos 6 caracteres.',
        ];
    }
}
