<?php

/**
 * Validación del alta/edición de una tarea por un administrador.
 *
 * El admin SÍ tiene que asignar operario obligatoriamente
 * (PDF: "No se permitirá crear tareas sin asignar operario a los administradores").
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TareaRequest extends FormRequest
{
    public function authorize(): bool
    {
        $usuario = $this->user();
        return $usuario && $usuario->esAdmin();
    }

    public function rules(): array
    {
        return [
            'cliente_id'        => ['required', 'exists:clientes,id'],
            'personaNombre'     => ['required', 'string', 'max:150'],
            'telefono'          => ['required', 'regex:/^[0-9+\s\-]{6,20}$/'],
            'correo'            => ['required', 'email', 'max:150'],
            'descripcionTarea'  => ['required', 'string'],
            'direccionTarea'    => ['nullable', 'string', 'max:200'],
            'poblacion'         => ['nullable', 'string', 'max:100'],
            // CP: si viene, debe tener 5 dígitos
            'codigoPostal'      => ['nullable', 'regex:/^[0-9]{5}$/'],
            // Provincia: debe existir en la tabla provincias
            'provincia'         => ['nullable', Rule::exists('provincias', 'codigo')],
            'estadoTarea'       => ['required', Rule::in(['P', 'R', 'C'])],
            // Operario obligatorio para los administradores
            'operario_id'       => ['required', 'exists:empleados,id'],
            // Fecha de realización: obligatoria y posterior a hoy
            'fechaRealizacion'  => ['required', 'date', 'after:today'],
            'anotacionesAnteriores' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'cliente_id.required'       => 'Debe seleccionar un cliente.',
            'personaNombre.required'    => 'El nombre de la persona de contacto es obligatorio.',
            'telefono.required'         => 'El teléfono es obligatorio.',
            'telefono.regex'            => 'El teléfono no tiene un formato válido.',
            'correo.required'           => 'El correo es obligatorio.',
            'correo.email'              => 'El correo no tiene un formato válido.',
            'descripcionTarea.required' => 'La descripción es obligatoria.',
            'codigoPostal.regex'        => 'El código postal debe tener 5 números.',
            'provincia.exists'          => 'La provincia seleccionada no es válida.',
            'estadoTarea.required'      => 'Debe seleccionar un estado.',
            'operario_id.required'      => 'Debe asignar un operario.',
            'operario_id.exists'        => 'El operario seleccionado no existe.',
            'fechaRealizacion.required' => 'La fecha de realización es obligatoria.',
            'fechaRealizacion.after'    => 'La fecha de realización debe ser posterior a hoy.',
        ];
    }
}
