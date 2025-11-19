<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('taller', function (Blueprint $table) {
            $table->id();

            $table->string('nombre'); // Nombre del taller

            // Turno: mañana o tarde
            $table->enum('turno', ['mañana', 'tarde']);

            // Horario
            $table->time('hora_inicio');
            $table->time('hora_fin');

            $table->string('sede'); // Sede del taller

            // Fechas
            $table->date('fecha_inicio');
            $table->date('fecha_fin');

            $table->integer('capacidad_alumnos'); // Capacidad

            $table->text('descripcion')->nullable(); // Descripción del taller

            // Requisitos, separados por comas
            $table->text('requisitos')->nullable();

            // Temario del taller
            $table->longText('temario')->nullable();

            // Estado del taller
            // disponible = aún hay cupos
            // lleno = capacidad alcanzada
            $table->enum('estado', ['disponible', 'lleno'])->default('disponible');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taller');
    }
};
// Hola