<?php

/**
 * Validación del formulario con el que un CLIENTE registra una incidencia
 * sin estar logueado (PDF: "Los clientes podrán registrar una incidencia
 * introduciendo los mismos datos que un administrador con la restricción
 * de que no asignarán el operario...").
 *
 * Además el cliente tiene que introducir su CIF y teléfono para que el
 * sistema pueda identificarlo.
 *
 * @author  Alumno DWES
 * @version 1.0
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IncidenciaClienteRequest extends FormRequest
{
    /** Acceso público: cualquiera puede enviar el form (luego se verifica CIF+tlf). */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Autenticación del cliente: CIF + teléfono
            'cif'               => ['required', 'string', 'max:20'],
            'telefonoCliente'   => ['required', 'string', 'max:20'],

            // Mismos datos que la tarea pero SIN operario_id
            'personaNombre'     => ['required', 'string', 'max:150'],
            'telefono'          => ['required', 'regex:/^[0-9+\s\-]{6,20}$/'],
            'correo'            => ['required', 'email', 'max:150'],
            'descripcionTarea'  => ['required', 'string'],
            'direccionTarea'    => ['nullable', 'string', 'max:200'],
            'poblacion'         => ['nullable', 'string', 'max:100'],
            'codigoPostal'      => ['nullable', 'regex:/^[0-9]{5}$/'],
            'provincia'         => ['nullable', Rule::exists('provincias', 'codigo')],
            'fechaRealizacion'  => ['required', 'date', 'after:today'],
            'anotacionesAnteriores' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'cif.required'              => 'Introduzca el CIF del cliente.',
            'telefonoCliente.required'  => 'Introduzca el teléfono del cliente.',
            'personaNombre.required'    => 'El nombre de la persona de contacto es obligatorio.',
            'telefono.required'         => 'El teléfono es obligatorio.',
            'telefono.regex'            => 'El teléfono no tiene un formato válido.',
            'correo.required'           => 'El correo es obligatorio.',
            'correo.email'              => 'El correo no tiene un formato válido.',
            'descripcionTarea.required' => 'La descripción es obligatoria.',
            'codigoPostal.regex'        => 'El código postal debe tener 5 números.',
            'fechaRealizacion.required' => 'La fecha de realización es obligatoria.',
            'fechaRealizacion.after'    => 'La fecha de realización debe ser posterior a hoy.',
        ];
    }
}
