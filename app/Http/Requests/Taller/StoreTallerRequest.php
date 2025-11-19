<?php

namespace App\Http\Requests\Taller;

use Illuminate\Foundation\Http\FormRequest;

class StoreTallerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre'            => 'required|string|max:255',
            'turno'             => 'required|in:mañana,tarde',
            'hora_inicio'       => 'required|date_format:H:i',
            'hora_fin'          => 'required|date_format:H:i|after:hora_inicio',
            'sede'              => 'required|string|max:255',
            'fecha_inicio'      => 'required|date',
            'fecha_fin'         => 'required|date|after_or_equal:fecha_inicio',
            'capacidad_alumnos' => 'required|integer|min:1|max:50',
            'descripcion'       => 'nullable|string',
            'requisitos'        => 'nullable|string',
            'temario'           => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required'            => 'El nombre del taller es obligatorio.',
            'turno.required'             => 'El turno es obligatorio.',
            'turno.in'                   => 'El turno debe ser mañana o tarde.',
            'hora_inicio.required'       => 'La hora de inicio es obligatoria.',
            'hora_inicio.date_format'    => 'La hora de inicio debe tener formato HH:MM.',
            'hora_fin.required'          => 'La hora de fin es obligatoria.',
            'hora_fin.date_format'       => 'La hora de fin debe tener formato HH:MM.',
            'hora_fin.after'             => 'La hora de fin debe ser después de la hora de inicio.',
            'sede.required'              => 'La sede es obligatoria.',
            'fecha_inicio.required'      => 'La fecha de inicio es obligatoria.',
            'fecha_fin.required'         => 'La fecha de fin es obligatoria.',
            'fecha_fin.after_or_equal'   => 'La fecha de fin no puede ser anterior a la fecha de inicio.',
            'capacidad_alumnos.required' => 'La capacidad de alumnos es obligatoria.',
            'capacidad_alumnos.integer'  => 'La capacidad debe ser un número entero.',
            'capacidad_alumnos.min'      => 'La capacidad debe ser al menos 1.',
            'capacidad_alumnos.max' => 'La capacidad máxima permitida es 50 alumnos.',
        ];
    }
}
