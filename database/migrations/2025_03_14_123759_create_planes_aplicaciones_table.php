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
        Schema::create('planes_aplicaciones', function (Blueprint $table) {
            $table->id(); // ID autoincremental (equivalente a INT PRIMARY KEY AUTO_INCREMENT)
                $table->unsignedBigInteger('id_estado')->default(1);
                $table->enum('nombre_plan', [
                    "Free", 
                    "Basic", 
                    "Pro+", 
                    "Estellar",
                    ])->nullable(false);
                $table->timestamp('fecha_creacion')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('fecha_modificacion')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
                
                // Si necesitas una relación con otra tabla, por ejemplo, estados:
                $table->foreign('id_estado')->references('id')->on('estados')->onDelete('cascade');
        });

         // Insertar datos iniciales
         DB::table('planes_aplicaciones')->insert([
            ['nombre_plan' => 'Free'],
            ['nombre_plan' => 'Basic'],
            ['nombre_plan' => 'Pro+'],
            ['nombre_plan' => 'Estellar'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('planes_aplicaciones');
    }
};
