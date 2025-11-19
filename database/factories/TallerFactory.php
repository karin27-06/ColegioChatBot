<?php

namespace Database\Factories;

use App\Models\Taller;
use Illuminate\Database\Eloquent\Factories\Factory;

class TallerFactory extends Factory
{
    protected $model = Taller::class;

    public function definition(): array
    {
        // Fecha de inicio entre hoy y 30 días después
        $fechaInicio = $this->faker->dateTimeBetween('now', '+30 days');
        // Fecha fin entre la fecha de inicio y +60 días
        $fechaFin = $this->faker->dateTimeBetween($fechaInicio, '+60 days');

        // Horarios
        $horaInicio = $this->faker->dateTimeBetween('08:00', '14:00');
        $horaFin = (clone $horaInicio)->modify('+2 hours');

        // Capacidad entre 10 y 50
        $capacidad = $this->faker->numberBetween(10, 50);

        return [
            'nombre'            => $this->faker->sentence(3),
            'turno'             => $this->faker->randomElement(['mañana', 'tarde']),
            'hora_inicio'       => $horaInicio->format('H:i'),
            'hora_fin'          => $horaFin->format('H:i'),
            'sede'              => $this->faker->city(),
            'fecha_inicio'      => $fechaInicio->format('Y-m-d'),
            'fecha_fin'         => $fechaFin->format('Y-m-d'),
            'capacidad_alumnos' => $capacidad,
            'descripcion'       => $this->faker->optional()->paragraph(),
            'requisitos'        => $this->faker->optional()->sentence(10),
            'temario'           => $this->faker->optional()->paragraph(5),
            'estado'            => $capacidad === 50 ? 'lleno' : 'disponible',
        ];
    }
}
