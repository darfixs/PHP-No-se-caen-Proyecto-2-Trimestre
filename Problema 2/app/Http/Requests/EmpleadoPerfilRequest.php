<?php

/**
 * Validación del formulario en el que un empleado modifica
 * sus datos personales (correo y fecha de alta), según el PDF:
 *
 *   "Cada empleado a su vez podrá modificar sus datos de contacto
 *    Correo y fecha de alta."
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmpleadoPerfilRequest extends FormRequest
{
    /** Cualquier empleado autenticado puede editar SU propio perfil. */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $id = $this->user()->id; // editándome a mí mismo

        return [
            'correo'     => ['required', 'email', 'max:150', Rule::unique('empleados', 'correo')->ignore($id)],
            'fecha_alta' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'correo.required'     => 'El correo es obligatorio.',
            'correo.email'        => 'El correo no tiene un formato válido.',
            'correo.unique'       => 'Ese correo ya está en uso por otro empleado.',
            'fecha_alta.required' => 'La fecha de alta es obligatoria.',
        ];
    }
}
