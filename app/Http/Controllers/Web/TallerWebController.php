<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Taller;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;

class TallerWebController extends Controller
{
    public function index(): Response
    {
        Gate::authorize('viewAny', Taller::class);

        return Inertia::render('panel/Taller/indexTaller');
    }
}
