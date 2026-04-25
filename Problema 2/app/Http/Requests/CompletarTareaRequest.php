<?php

/**
 * Validación del formulario con el que un OPERARIO completa una tarea
 * (cambiando el estado y añadiendo anotaciones / fichero resumen).
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompletarTareaRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Cualquier empleado autenticado (la restricción a "tarea propia"
        // la aplico en el controlador).
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'estadoTarea'            => ['required', Rule::in(['P', 'R', 'C'])],
            'anotacionesPosteriores' => ['nullable', 'string'],
            // Fichero resumen opcional - limito a 5MB y a tipos razonables
            'ficheroResumen'         => ['nullable', 'file', 'max:5120',
                                         'mimes:pdf,jpg,jpeg,png,doc,docx,txt'],
        ];
    }

    public function messages(): array
    {
        return [
            'estadoTarea.required'  => 'Debe indicar el estado de la tarea.',
            'ficheroResumen.max'    => 'El fichero no puede superar los 5 MB.',
            'ficheroResumen.mimes'  => 'El tipo de fichero no está permitido.',
        ];
    }
}
