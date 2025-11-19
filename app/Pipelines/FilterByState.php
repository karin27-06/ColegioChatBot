<?php

namespace App\Pipelines;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class FilterByState
{
    public function __construct(private $state) {}

    public function __invoke(Builder $builder, Closure $next)
    {
        if (is_null($this->state) || $this->state === '') {
            return $next($builder);
        }

        $value = strtolower(trim($this->state));

        $builder->where(function ($q) use ($value) {

            // Filtrar por estado (string)
            if (Schema::hasColumn($q->getModel()->getTable(), 'estado')) {
                $q->orWhere('estado', 'ILIKE', "%{$value}%");
            }

            // Filtrar por turno
            if (Schema::hasColumn($q->getModel()->getTable(), 'turno')) {
                $q->orWhere('turno', 'ILIKE', "%{$value}%");
            }

            // Filtrar por state (boolean)
            if (Schema::hasColumn($q->getModel()->getTable(), 'state')) {

                // true, false, activo, inactivo
                $boolean = match ($value) {
                    '1', 'true', 'activo', 'si', 'sí' => true,
                    '0', 'false', 'inactivo', 'no'    => false,
                    default => null
                };

                if (!is_null($boolean)) {
                    $q->orWhere('state', $boolean);
                }
            }
        });

        return $next($builder);
    }
}
