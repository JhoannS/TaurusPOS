<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ReducirDuracionMembresias extends Command
{
    protected $signature = 'membresias:reducir-dias';
    protected $description = 'Reduce un día en la duración de las membresías activas y cambia el estado cuando llegue a 0';

    public function handle()
    {
        // Reducir los días restantes en aplicaciones activas
        DB::table('aplicaciones_web')
            ->join('membresias', 'aplicaciones_web.id_membresia', '=', 'membresias.id')
            ->join('tiendas_sistematizadas', 'tiendas_sistematizadas.id_aplicacion_web', '=', 'aplicaciones_web.id')
            ->join('token_accesos', 'tiendas_sistematizadas.id_token', '=', 'token_accesos.id')
            ->where('token_accesos.id_estado', 1)
            ->where('aplicaciones_web.dias_restantes', '>', 0)
            ->update([
                'aplicaciones_web.dias_restantes' => DB::raw('dias_restantes - 1'),
            ]);

        // Cambiar estado en pagos_membresia cuando dias_restantes sea 0
        DB::table('pagos_membresia')
            ->join('clientes_taurus', 'pagos_membresia.id_cliente', '=', 'clientes_taurus.id')
            ->join('tiendas_sistematizadas', 'pagos_membresia.id_tienda', '=', 'tiendas_sistematizadas.id')
            ->join('aplicaciones_web', 'tiendas_sistematizadas.id_aplicacion_web', '=', 'aplicaciones_web.id')
            ->where('aplicaciones_web.dias_restantes', '<=', 0) // Si ya está en 0 o menos
            ->update([
                'pagos_membresia.id_estado' => 9, // Estado "vencido"
            ]);

            $this->info('Días de membresías reducidos y estados actualizados.');
    }
}
