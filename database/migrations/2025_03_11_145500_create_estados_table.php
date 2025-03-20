<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateEstadosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('estados', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_estado', [
                'Activo',
                'Inactivo',
                'En curso',
                'Finalizado',
                'En espera',
                'Cancelado',
                'Reservado',
                'Pagada',
                'Pendiente',
                'Estable',
                'Peligro',
                'Revisión',
                'Suspendida'
            ])->nullable(false);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();
        });

        // Insertar datos iniciales
        DB::table('estados')->insert([
            ['tipo_estado' => 'Activo'],
            ['tipo_estado' => 'Inactivo'],
            ['tipo_estado' => 'En curso'],
            ['tipo_estado' => 'Finalizado'],
            ['tipo_estado' => 'En espera'],
            ['tipo_estado' => 'Cancelado'],
            ['tipo_estado' => 'Reservado'],
            ['tipo_estado' => 'Pagada'],
            ['tipo_estado' => 'Pendiente'],
            ['tipo_estado' => 'Estable'],
            ['tipo_estado' => 'Peligro'],
            ['tipo_estado' => 'Revisión'],
            ['tipo_estado' => 'Suspendida'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estados');
    }
}

