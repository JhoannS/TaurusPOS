<?php

namespace App\Http\Controllers;

use App\Models\PlanAplicacion;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function mostrarDetalles($idPlan)
    {
        $detalles = PlanAplicacion::with('detalles')->find($idPlan);

        if (!$detalles) {
            abort(404, 'Plan de aplicación no encontrado');
        }

        return Inertia::render('Apps/Essentials/Administrador/Dashboard/Dashboard', [
            'detallesPlan' => $detalles->detalles
        ]);
    }
}
