<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReducirDuracionMembresias extends Command
{
    protected $signature = 'membresias:reducir-dias';
    protected $description = 'Reduce un día en la duración de las membresías activas y cambia el estado cuando llegue a 0';

    public function handle()
    {
        $hoy = Carbon::now()->toDateString();

        DB::table('pagos_membresia')
            ->join('tiendas_sistematizadas', 'pagos_membresia.id_tienda', '=', 'tiendas_sistematizadas.id')
            ->join('token_accesos', 'tiendas_sistematizadas.id_token', '=', 'token_accesos.id')
            ->where('token_accesos.id_estado', 1) // Token activo
            ->where('pagos_membresia.dias_restantes', '>', 0)
            ->whereDate('pagos_membresia.fecha_activacion', '<=', $hoy)
            ->update([
                'pagos_membresia.dias_restantes' => DB::raw('dias_restantes - 1'),
            ]);

        // 2. Cambiar el estado a vencido (id_estado = 9) si días restantes llegan a 0 o menos
        DB::table('pagos_membresia')
            ->where('dias_restantes', '<=', 0)
            ->update([
                'id_estado' => 9,
            ]);

        $this->info('Días de membresías reducidos correctamente y estados actualizados si era necesario.');
    }
}
