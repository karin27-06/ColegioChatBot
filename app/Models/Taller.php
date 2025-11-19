<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taller extends Model
{
    use HasFactory;

    protected $table = 'taller'; // nombre de la tabla

    protected $fillable = [
        'nombre',
        'turno',
        'hora_inicio',
        'hora_fin',
        'sede',
        'fecha_inicio',
        'fecha_fin',
        'capacidad_alumnos',
        'descripcion',
        'requisitos',
        'temario',
    ];

    protected $casts = [
        'fecha_inicio'      => 'date',
        'fecha_fin'         => 'date',
        'hora_inicio'       => 'string',
        'hora_fin'          => 'string',
        'capacidad_alumnos' => 'integer',
        'estado'            => 'string',
    ];

    // Ejemplo de relaciones (POR SI LUEGO AGREGAS otras tablas)
    /*
    public function alumnos()
    {
        return $this->hasMany(AlumnoTaller::class, 'taller_id');
    }
    */

    /**
     * Método opcional por si deseas impedir eliminaciones con datos relacionados.
     */
    public function tieneRelaciones(): bool
    {
        // Modifícalo cuando agregues relaciones reales
        return false;
    }
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($taller) {
            if ($taller->capacidad_alumnos >= 50) {
                $taller->estado = 'lleno';
            } else {
                $taller->estado = 'disponible';
            }
        });
    }
}
