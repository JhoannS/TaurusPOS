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
        // 1. Reducir días restantes en pagos_membresia activos (token activo y días > 0)
        DB::table('pagos_membresia')
            ->join('tiendas_sistematizadas', 'pagos_membresia.id_tienda', '=', 'tiendas_sistematizadas.id')
            ->join('token_accesos', 'tiendas_sistematizadas.id_token', '=', 'token_accesos.id')
            ->where('token_accesos.id_estado', 1) // Token activo
            ->where('pagos_membresia.dias_restantes', '>', 0)
            ->update([
                'pagos_membresia.dias_restantes' => DB::raw('dias_restantes - 1'),
            ]);

        // 2. Cambiar estado cuando los días se agoten (estado vencido: id 9)
        DB::table('pagos_membresia')
            ->where('dias_restantes', '<=', 0)
            ->update([
                'id_estado' => 9, // Estado vencido
            ]);

        $this->info('Días de membresías reducidos correctamente y estados actualizados si era necesario.');
    }
}
