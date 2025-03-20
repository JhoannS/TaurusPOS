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
        Schema::create('detalles_plan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_plan_aplicacion');
            $table->integer('cantidad_sucursales')->nullable(false);
            $table->integer('cantidad_empleados')->nullable(false);
            $table->integer('cantidad_proveedores')->nullable(false);
            $table->integer('cantidad_facturacion_electronica')->nullable(false);
            $table->integer('cantidad_facturacion_fisica')->nullable(false);
            $table->integer('cantidad_productos')->nullable(false);
            $table->integer('cantidad_servicios')->nullable(false);
            $table->integer('cantidad_mesas')->nullable(false);
            $table->enum('manejo_reservas', ["Si", "No aplica"])->nullable(false);
            $table->enum('manejo_contabilidad', ["Si", "No aplica"])->nullable(false);
            $table->enum('manejo_pos', ["Si", "No aplica"])->nullable(false);
            $table->enum('manejo_control_gastos', ["Si", "No aplica"])->nullable(false);
            $table->enum('manejo_etiquetado_barras', ["Si", "No aplica"])->nullable(false);
            $table->enum('manejo_codigos_qr', ["Si", "No aplica"])->nullable(false);
            $table->enum('manejo_orden_trabajo', ["Si", "No aplica"])->nullable(false);
            $table->enum('manejo_encuestas', ["Si", "No aplica"])->nullable(false);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('id_plan_aplicacion')->references('id')->on('planes_aplicaciones')->onDelete('cascade');
        });

         // Insertar datos iniciales
         DB::table('detalles_plan')->insert([
            [
                'id_plan_aplicacion' => '1',
                'cantidad_sucursales' => '99999',
                'cantidad_empleados' => '99999',
                'cantidad_proveedores' => '99999',
                'cantidad_facturacion_electronica' => '99999',
                'cantidad_facturacion_fisica' => '99999',
                'cantidad_productos' => '99999',
                'cantidad_servicios' => '99999',
                'cantidad_mesas' => '99999',
                'manejo_reservas' => 'Si',
                'manejo_contabilidad' => 'Si',
                'manejo_pos' => 'Si',
                'manejo_control_gastos' => 'Si',
                'manejo_etiquetado_barras' => 'Si',
                'manejo_codigos_qr' => 'Si',
                'manejo_orden_trabajo' => 'Si',
                'manejo_encuestas' => 'Si',
            ],

            [
                'id_plan_aplicacion' => '2',
                'cantidad_sucursales' => '1',
                'cantidad_empleados' => '10',
                'cantidad_proveedores' => '5',
                'cantidad_facturacion_electronica' => '10',
                'cantidad_facturacion_fisica' => '100',
                'cantidad_productos' => '30',
                'cantidad_servicios' => '25',
                'cantidad_mesas' => '10',
                'manejo_reservas' => 'No aplica',
                'manejo_contabilidad' => 'No aplica',
                'manejo_pos' => 'Si',
                'manejo_control_gastos' => 'No aplica',
                'manejo_etiquetado_barras' => 'No aplica',
                'manejo_codigos_qr' => 'No aplica',
                'manejo_orden_trabajo' => 'No aplica',
                'manejo_encuestas' => 'No aplica',
    
            ],

            [
                'id_plan_aplicacion' => '3',
                'cantidad_sucursales' => '3',
                'cantidad_empleados' => '20',
                'cantidad_proveedores' => '10',
                'cantidad_facturacion_electronica' => '60',
                'cantidad_facturacion_fisica' => '200',
                'cantidad_productos' => '90',
                'cantidad_servicios' => '80',
                'cantidad_mesas' => '30',
                'manejo_reservas' => 'No aplica',
                'manejo_contabilidad' => 'No aplica',
                'manejo_pos' => 'Si',
                'manejo_control_gastos' => 'Si',
                'manejo_etiquetado_barras' => 'No aplica',
                'manejo_codigos_qr' => 'No aplica',
                'manejo_orden_trabajo' => 'No aplica',
                'manejo_encuestas' => 'No aplica',
    
            ],

            [
                'id_plan_aplicacion' => '4',
                'cantidad_sucursales' => '99999',
                'cantidad_empleados' => '99999',
                'cantidad_proveedores' => '99999',
                'cantidad_facturacion_electronica' => '99999',
                'cantidad_facturacion_fisica' => '99999',
                'cantidad_productos' => '99999',
                'cantidad_servicios' => '99999',
                'cantidad_mesas' => '99999',
                'manejo_reservas' => 'Si',
                'manejo_contabilidad' => 'Si',
                'manejo_pos' => 'Si',
                'manejo_control_gastos' => 'Si',
                'manejo_etiquetado_barras' => 'Si',
                'manejo_codigos_qr' => 'Si',
                'manejo_orden_trabajo' => 'Si',
                'manejo_encuestas' => 'Si',
    
            ],
        ]);
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalles_plan');
    }
};