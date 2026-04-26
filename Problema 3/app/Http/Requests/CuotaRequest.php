<?php

/**
 * Validación para el alta/edición de una cuota individual.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CuotaRequest extends FormRequest
{
    /** Solo admin gestiona cuotas. */
    public function authorize(): bool
    {
        $usuario = $this->user();
        return $usuario && $usuario->esAdmin();
    }

    public function rules(): array
    {
        return [
            'cliente_id'    => ['required', 'exists:clientes,id'],
            'concepto'      => ['required', 'string', 'max:200'],
            'fecha_emision' => ['required', 'date'],
            'importe'       => ['required', 'numeric', 'min:0'],
            'moneda'        => ['required', 'string', 'size:3'],
            'pagada'        => ['nullable', 'boolean'],
            'fecha_pago'    => ['nullable', 'date'],
            'notas'         => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'cliente_id.required' => 'Debe seleccionar un cliente.',
            'cliente_id.exists'   => 'El cliente seleccionado no existe.',
            'concepto.required'   => 'El concepto es obligatorio.',
            'importe.required'    => 'El importe es obligatorio.',
            'importe.numeric'     => 'El importe debe ser numérico.',
        ];
    }
}
