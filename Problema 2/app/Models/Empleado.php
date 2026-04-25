<?php

/**
 * Modelo Eloquent para la entidad Empleado.
 *
 * Un empleado puede ser Administrador u Operario. Además de ser un
 * registro más en la BD, también es el "User" que Laravel utiliza
 * para autenticación (login), por eso extiende de Authenticatable.
 *
 * La lógica de consulta vive aquí (NO en los controladores), como
 * exige el PDF: "Se deberá utilizar obligatoriamente Eloquent
 * incluyendo la lógica de consulta en el modelo o clases auxiliares,
 * NO en los controladores".
 *
 * @author  JDAS DWES
 * @version 1.0
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Empleado extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Campos asignables en masa (mass assignment).
     * Todo lo que se puede rellenar desde el formulario.
     *
     * @var array<string>
     */
    protected $fillable = [
        'dni', 'nombre', 'correo', 'telefono', 'direccion',
        'fecha_alta', 'tipo', 'password',
    ];

    /** Campos ocultos en las respuestas JSON (seguridad). */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /** Casteos automáticos de tipos. */
    protected $casts = [
        'fecha_alta'        => 'date',
        'email_verified_at' => 'datetime',
        'password'          => 'hashed', // Laravel 10+ hashea automáticamente
    ];

    /**
     * Laravel por defecto busca el campo "email", pero yo lo llamo "correo".
     * Sobrescribiendo este método le digo a Laravel dónde tiene que mirar.
     */
    public function getEmailForPasswordReset(): string
    {
        return $this->correo;
    }

    /**
     * Le indico a Laravel que el "nombre de usuario" para el login
     * es el campo 'correo'.
     */
    public function username(): string
    {
        return 'correo';
    }

    /* =======================================================================
     *  RELACIONES
     * ======================================================================= */

    /** Un empleado (operario) puede tener muchas tareas asignadas. */
    public function tareasAsignadas(): HasMany
    {
        return $this->hasMany(Tarea::class, 'operario_id');
    }

    /* =======================================================================
     *  HELPERS / LÓGICA DE NEGOCIO
     * ======================================================================= */

    /** ¿Este empleado es administrador? */
    public function esAdmin(): bool
    {
        return $this->tipo === 'Administrador';
    }

    /** ¿Este empleado es operario? */
    public function esOperario(): bool
    {
        return $this->tipo === 'Operario';
    }

    /* =======================================================================
     *  CONSULTAS (aquí centralizo las consultas para que NO estén en los
     *  controladores, tal como pide el PDF)
     * ======================================================================= */

    /**
     * Lista paginada de todos los empleados.
     *
     * @param  int $porPagina Elementos por página.
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public static function listado(int $porPagina = 10)
    {
        return self::orderBy('nombre')->paginate($porPagina);
    }

    /**
     * Devuelve solo los empleados de tipo Operario.
     * Sirve para rellenar el <select> del alta/edición de tareas.
     *
     * @return \Illuminate\Support\Collection
     */
    public static function operarios()
    {
        return self::where('tipo', 'Operario')
                   ->orderBy('nombre')
                   ->get();
    }
}
