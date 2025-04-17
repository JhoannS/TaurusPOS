<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReducirDuracionMembresias extends Command
{
    protected $signature = 'membresias:reducir-dias';
    protected $description = 'Reduce un día en la duración de las membresías activas, cambia su estado y desactiva tokens si vencen.';

    public function handle()
    {
        $hoy = Carbon::now()->toDateString();

        // 1. Reducir días restantes (solo si token activo, días > 0 y fecha activación <= hoy)
        DB::table('pagos_membresia')
            ->join('tiendas_sistematizadas', 'pagos_membresia.id_tienda', '=', 'tiendas_sistematizadas.id')
            ->join('token_accesos', 'tiendas_sistematizadas.id_token', '=', 'token_accesos.id')
            ->where('token_accesos.id_estado', 1) // Token activo
            ->where('pagos_membresia.dias_restantes', '>', 0)
            ->whereDate('pagos_membresia.fecha_activacion', '<=', $hoy)
            ->update([
                'pagos_membresia.dias_restantes' => DB::raw('dias_restantes - 1'),
            ]);

        // 2. Cambiar estado de membresía a vencido (id_estado = 9)
        DB::table('pagos_membresia')
            ->where('dias_restantes', '<=', 0)
            ->update([
                'id_estado' => 9,
            ]);

        // 3. Desactivar token si la tienda tiene membresía vencida
        DB::table('token_accesos')
            ->join('tiendas_sistematizadas', 'token_accesos.id', '=', 'tiendas_sistematizadas.id_token')
            ->join('pagos_membresia', 'tiendas_sistematizadas.id', '=', 'pagos_membresia.id_tienda')
            ->where('pagos_membresia.dias_restantes', '<=', 0)
            ->update([
                'token_accesos.id_estado' => 2, // Token inactivo
            ]);

        $this->info('Membresías actualizadas: días reducidos, estados vencidos y tokens desactivados si correspondía.');
    }
}
