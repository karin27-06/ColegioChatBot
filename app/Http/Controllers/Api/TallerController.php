<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Exports\TalleresExport;
use App\Http\Requests\Taller\StoreTallerRequest;
use App\Http\Requests\Taller\UpdateTallerRequest;
use App\Http\Resources\TallerResource;
use App\Models\Taller;
use App\Pipelines\FilterByName;
use App\Pipelines\FilterByState;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Gate;
use Maatwebsite\Excel\Facades\Excel;

class TallerController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Taller::class);

        $perPage = $request->input('per_page', 15);
        $search = $request->input('search');

        $query = app(Pipeline::class)
            ->send(Taller::query())
            ->through([
                new FilterByName($search),
                new FilterByState($request->input('state')),
            ])
            ->thenReturn();

        return TallerResource::collection($query->paginate($perPage));
    }

    public function store(StoreTallerRequest $request)
    {
        Gate::authorize('create', Taller::class);

        $validated = $request->validated();
        $taller = Taller::create($validated);

        return response()->json([
            'state' => true,
            'message' => 'Taller registrado correctamente.',
            'taller' => $taller,
        ]);
    }

    public function show(Taller $taller)
    {
        Gate::authorize('view', $taller);

        return response()->json([
            'state' => true,
            'message' => 'Taller encontrado',
            'taller' => new TallerResource($taller),
        ]);
    }

    public function update(UpdateTallerRequest $request, Taller $taller)
    {
        Gate::authorize('update', $taller);

        $validated = $request->validated();
        $taller->update($validated);

        return response()->json([
            'state' => true,
            'message' => 'Taller actualizado correctamente.',
            'taller' => $taller->refresh(),
        ]);
    }

    public function destroy(Taller $taller)
    {
        Gate::authorize('delete', $taller);

        if ($taller->tieneRelaciones()) {
            return response()->json([
                'state' => false,
                'message' => 'No se puede eliminar este taller porque tiene relaciones con otros registros.',
            ], 400);
        }

        $taller->delete();

        return response()->json([
            'state' => true,
            'message' => 'Taller eliminado correctamente.',
        ]);
    }
}
