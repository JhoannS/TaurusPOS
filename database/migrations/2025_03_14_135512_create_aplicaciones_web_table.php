<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('aplicaciones_web', function (Blueprint $table) {
            $table->id(); // ID autoincremental (equivalente a INT PRIMARY KEY AUTO_INCREMENT)
            $table->unsignedBigInteger('id_estado')->default(1);
            $table->unsignedBigInteger('id_plan_aplicacion')->nullable(false);
            $table->unsignedBigInteger('id_membresia')->nullable(false);
            $table->string('nombre_app')->nullable(false);
            $table->timestamp('fecha_creacion')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('fecha_modificacion')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();

            // Si necesitas una relación con otra tabla, por ejemplo, estados:
            $table->foreign('id_estado')->references('id')->on('estados')->onDelete('cascade');
            $table->foreign('id_plan_aplicacion')->references('id')->on('planes_aplicaciones')->onDelete('cascade');
            $table->foreign('id_membresia')->references('id')->on('membresias')->onDelete('cascade');
        });

        // Insertar datos iniciales
        DB::table('aplicaciones_web')->insert([
            [
                'id_plan_aplicacion' => '1',
                'id_membresia' => '1',
                'nombre_app' => 'Machine'
            ],
            [
                'id_plan_aplicacion' => '1',
                'id_membresia' => '1',
                'nombre_app' => 'Guru'
            ],
            [
                'id_plan_aplicacion' => '1',
                'id_membresia' => '1',
                'nombre_app' => 'Smart'
            ],
            [
                'id_plan_aplicacion' => '1',
                'id_membresia' => '1',
                'nombre_app' => 'Essentials'
            ],
            [
                'id_plan_aplicacion' => '1',
                'id_membresia' => '1',
                'nombre_app' => 'Shopper'
            ],

            [
                'id_plan_aplicacion' => '2',
                'id_membresia' => '2',
                'nombre_app' => 'Machine'
            ],
            [
                'id_plan_aplicacion' => '2',
                'id_membresia' => '2',
                'nombre_app' => 'Guru'
            ],
            [
                'id_plan_aplicacion' => '2',
                'id_membresia' => '2',
                'nombre_app' => 'Smart'
            ],
            [
                'id_plan_aplicacion' => '2',
                'id_membresia' => '2',
                'nombre_app' => 'Essentials'
            ],
            [
                'id_plan_aplicacion' => '2',
                'id_membresia' => '2',
                'nombre_app' => 'Shopper'
            ],

            [
                'id_plan_aplicacion' => '3',
                'id_membresia' => '3',
                'nombre_app' => 'Machine'
            ],
            [
                'id_plan_aplicacion' => '3',
                'id_membresia' => '3',
                'nombre_app' => 'Guru'
            ],
            [
                'id_plan_aplicacion' => '3',
                'id_membresia' => '3',
                'nombre_app' => 'Smart'
            ],
            [
                'id_plan_aplicacion' => '3',
                'id_membresia' => '3',
                'nombre_app' => 'Essentials'
            ],
            [
                'id_plan_aplicacion' => '3',
                'id_membresia' => '3',
                'nombre_app' => 'Shopper'
            ],

            [
                'id_plan_aplicacion' => '4',
                'id_membresia' => '4',
                'nombre_app' => 'Machine'
            ],
            [
                'id_plan_aplicacion' => '4',
                'id_membresia' => '4',
                'nombre_app' => 'Guru'
            ],
            [
                'id_plan_aplicacion' => '4',
                'id_membresia' => '4',
                'nombre_app' => 'Smart'
            ],
            [
                'id_plan_aplicacion' => '4',
                'id_membresia' => '4',
                'nombre_app' => 'Essentials'
            ],
            [
                'id_plan_aplicacion' => '4',
                'id_membresia' => '4',
                'nombre_app' => 'Shopper'
            ],
            [
                'id_plan_aplicacion' => '4',
                'id_membresia' => '6',
                'nombre_app' => 'TaurusCO'
            ],


        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aplicaciones_web');
    }
};
