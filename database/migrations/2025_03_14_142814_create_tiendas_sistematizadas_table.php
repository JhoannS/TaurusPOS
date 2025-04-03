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
        Schema::create('tiendas_sistematizadas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_estado')->default(1);
            $table->unsignedBigInteger('id_token')->nullable();
            $table->unsignedBigInteger('id_aplicacion_web')->nullable();
            $table->string('nombre_tienda');
            $table->string('email_tienda')->unique();
            $table->string('telefono_tienda')->unique();
            $table->string('direccion_tienda');
            $table->string('barrio_tienda');
        
            $table->timestamp('fecha_creacion')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('fecha_modificacion')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
        
            $table->foreign('id_estado')->references('id')->on('estados')->onDelete('cascade');
            $table->foreign('id_aplicacion_web')->references('id')->on('aplicaciones_web')->onDelete('cascade');
        });

        DB::table('tiendas_sistematizadas')->insert([
            [
                'id_token' => '1',
                'id_aplicacion_web' => '26',
                'nombre_tienda'=> 'Taurus Comunity CO',
                'email_tienda' => 'tauruscomunityco@gmail.com',
                'telefono_tienda' => '3219631459',
                'direccion_tienda' => 'Calle 58c sur #45 03',
                'barrio_tienda' => 'Arborizadora Baja',
            ],

        ]);
        
        
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tiendas_sistematizadas');
    }
};
