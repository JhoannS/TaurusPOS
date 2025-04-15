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
        Schema::create('pagos_membresia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cliente');
            $table->unsignedBigInteger('id_tienda');
            $table->string('monto_total')->default(0);
            $table->unsignedBigInteger('id_medio_pago');
            $table->unsignedBigInteger('id_estado');
        
            $table->timestamp('fecha_creacion')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('fecha_pago')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
            $table->timestamp('fecha_activacion')->nullable();
        
            $table->foreign('id_cliente')->references('id')->on('clientes_taurus');
            $table->foreign('id_tienda')->references('id')->on('tiendas_sistematizadas');
            $table->foreign('id_medio_pago')->references('id')->on('medios_pago');
            $table->foreign('id_estado')->references('id')->on('estados');
        });

        DB::table('pagos_membresia')->insert([
            [
                'id_cliente' => '1',
                'id_tienda' => '1',
                'monto_total' => '9200000',
                'id_medio_pago' => '1',
                'id_estado' => '8',
                'fecha_pago'       => now(),
                'fecha_activacion' => now(),
            ],

        ]);
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos_membresia');
    }
};
