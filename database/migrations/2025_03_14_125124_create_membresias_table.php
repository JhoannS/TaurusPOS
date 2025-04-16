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
        Schema::create('membresias', function (Blueprint $table) {
            $table->id(); // ID autoincremental (clave primaria)
            $table->unsignedBigInteger('id_estado')->default(1); // Relación con la tabla estados
        
            $table->string('nombre_membresia'); // Nombre de la membresía
            $table->string('precio'); // Precio de la membresía
            $table->string('periodo'); // Periodo (ejemplo: mensual, anual)
            $table->integer('duracion'); // Duración en días, meses, o años
            $table->text('descripcion')->nullable(); // Descripción opcional de la membresía
        
            // Fechas de creación y modificación
            $table->timestamp('fecha_creacion')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('fecha_modificacion')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
        
            // Relaciones
            $table->foreign('id_estado')->references('id')->on('estados')->onDelete('cascade');
        });
        

        DB::table('membresias')->insert([
            [
                'nombre_membresia' => 'Free',
                'precio' => '0',
                'periodo'=> 'Semanal',
                'duracion' => '7',
                'descripcion' => 'Plan de prueba totalmente gratuito.',
            ],
            [
                'nombre_membresia' => 'Platino',
                'precio' => '75800',
                'periodo'=> 'Mensual',
                'duracion' => '30',
                'descripcion' => 'Plan de modalidad mensual, con previo aviso 5 dias antes.',
            ],
            [
                'nombre_membresia' => 'Diamante',
                'precio' => '210400',
                'periodo'=> 'Trimestral',
                'duracion' => '90',
                'descripcion' => 'Plan de modalidad cada 3 meses, con previo aviso 5 dias antes',
            ],
            [
                'nombre_membresia' => 'Esmeralda',
                'precio' => '435000',
                'periodo'=> 'Semestral',
                'duracion' => '180',
                'descripcion' => 'Plan de modalidad cada 6 meses, con previo aviso 5 dias antes',
            ],
            [
                'nombre_membresia' => 'Ruby',
                'precio' => '1285000',
                'periodo'=> 'Anual',
                'duracion' => '365',
                'descripcion' => 'Plan de modalidad cada 12 meses, con previo aviso 5 dias antes',
            ],
            [
                'nombre_membresia' => 'All star',
                'precio' => '9200000',
                'periodo'=> 'Infinito',
                'duracion' => '999999',
                'descripcion' => 'Plan sin terminación.',
            ],

        ]);
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membresias');
    }
};
