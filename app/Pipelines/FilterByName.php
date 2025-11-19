<?php

namespace App\Pipelines;

use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class FilterByName
{
    public function __construct(private ?string $search) {}

    public function __invoke(Builder $builder, Closure $next)
    {
        if (!$this->search) {
            return $next($builder);
        }

        $normalized = strtolower(trim(preg_replace('/\s+/', ' ', $this->search)));
        $terms = explode(' ', $normalized);

        $builder->where(function ($q) use ($terms) {

            foreach ($terms as $term) {

                $q->orWhere(function ($sub) use ($term) {

                    // Buscar en name SI EXISTE EN EL MODELO
                    if (in_array('name', $sub->getModel()->getFillable()) ||
                        Schema::hasColumn($sub->getModel()->getTable(), 'name')) {
                        $sub->where('name', 'ILIKE', "%{$term}%");
                    }

                    // Buscar en nombre (TU MODELO TALLER SÍ LO TIENE)
                    if (in_array('nombre', $sub->getModel()->getFillable()) ||
                        Schema::hasColumn($sub->getModel()->getTable(), 'nombre')) {
                        $sub->orWhere('nombre', 'ILIKE', "%{$term}%");
                    }

                    // Buscar por estado (string en tu modelo Taller)
                    $sub->orWhereRaw(
                        "estado ILIKE ?",
                        ["%{$term}%"]
                    );
                });
            }
        });

        return $next($builder);
    }
}
