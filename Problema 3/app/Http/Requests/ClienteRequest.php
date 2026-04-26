<?php

/**
 * Validación del alta/edición de un cliente.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClienteRequest extends FormRequest
{
    /** Solo el administrador gestiona clientes. */
    public function authorize(): bool
    {
        $usuario = $this->user();
        return $usuario && $usuario->esAdmin();
    }

    public function rules(): array
    {
        $id = $this->route('cliente');

        return [
            'cif'              => ['required', 'string', 'max:20', Rule::unique('clientes', 'cif')->ignore($id)],
            'nombre'           => ['required', 'string', 'max:150'],
            'telefono'         => ['required', 'regex:/^[0-9+\s\-]{6,20}$/'],
            'correo'           => ['required', 'email', 'max:150'],
            'cuenta_corriente' => ['nullable', 'string', 'max:34'],
            'pais_id'          => ['required', 'exists:paises,id'],
            'moneda'           => ['required', 'string', 'size:3'],
            'importe_cuota'    => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'cif.required'           => 'El CIF es obligatorio.',
            'cif.unique'             => 'Ya existe un cliente con ese CIF.',
            'nombre.required'        => 'El nombre del cliente es obligatorio.',
            'telefono.required'      => 'El teléfono es obligatorio.',
            'telefono.regex'         => 'El teléfono no tiene un formato válido.',
            'correo.email'           => 'El correo no tiene un formato válido.',
            'pais_id.required'       => 'Debe seleccionar un país.',
            'pais_id.exists'         => 'El país seleccionado no existe.',
            'moneda.required'        => 'La moneda es obligatoria.',
            'moneda.size'            => 'La moneda debe tener 3 caracteres (ej. EUR).',
            'importe_cuota.required' => 'El importe de la cuota es obligatorio.',
            'importe_cuota.numeric'  => 'El importe debe ser un número.',
            'importe_cuota.min'      => 'El importe no puede ser negativo.',
        ];
    }
}
