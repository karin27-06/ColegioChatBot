<?php

namespace Database\Seeders;

use App\Models\Taller;
use Illuminate\Database\Seeder;

class TallerSeeder extends Seeder
{
    public function run(): void
    {
        $talleres = [
            [
                'nombre' => 'Taller de Robótica Inicial',
                'turno' => 'mañana',
                'hora_inicio' => '08:00',
                'hora_fin' => '10:00',
                'sede' => 'Campus Central',
                'fecha_inicio' => '2025-01-15',
                'fecha_fin' => '2025-02-15',
                'capacidad_alumnos' => 25,
                'descripcion' => 'Introducción a la robótica educativa.',
                'requisitos' => 'Nociones básicas de informática',
                'temario' => 'Motores, sensores, estructuras y programación básica',
            ],
            [
                'nombre' => 'Taller de Programación en Python',
                'turno' => 'tarde',
                'hora_inicio' => '15:00',
                'hora_fin' => '17:00',
                'sede' => 'Laboratorio 2',
                'fecha_inicio' => '2025-03-01',
                'fecha_fin' => '2025-04-01',
                'capacidad_alumnos' => 30,
                'descripcion' => 'Fundamentos de Python orientado a proyectos.',
                'requisitos' => 'Conocimiento básico de computación',
                'temario' => 'Variables, condicionales, funciones, módulos',
            ],
            [
                'nombre' => 'Taller de Impresión 3D',
                'turno' => 'mañana',
                'hora_inicio' => '10:00',
                'hora_fin' => '12:00',
                'sede' => 'Centro de Innovación',
                'fecha_inicio' => '2025-02-01',
                'fecha_fin' => '2025-03-01',
                'capacidad_alumnos' => 50, // lleno
                'descripcion' => 'Uso de impresoras 3D y modelado básico.',
                'requisitos' => 'Ninguno',
                'temario' => 'Modelado, fusión de capas, parámetros de impresión',
            ],
            [
                'nombre' => 'Taller de Fotografía Digital',
                'turno' => 'tarde',
                'hora_inicio' => '14:00',
                'hora_fin' => '16:00',
                'sede' => 'Sala Multimedia',
                'fecha_inicio' => '2025-04-01',
                'fecha_fin' => '2025-05-01',
                'capacidad_alumnos' => 20,
                'descripcion' => 'Fundamentos de fotografía y edición básica.',
                'requisitos' => 'Contar con cámara (puede ser de celular)',
                'temario' => 'Composición, iluminación, edición',
            ],
        ];

        foreach ($talleres as $taller) {
            $taller['estado'] = $taller['capacidad_alumnos'] == 50 ? 'lleno' : 'disponible';
            Taller::create($taller);
        }

        // Factory opcional
        // Taller::factory(5000)->create();
    }
}
