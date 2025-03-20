<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateMediosPagoTable extends Migration
{
    public function up(): void
    {
        
            Schema::create('medios_pago', function (Blueprint $table) {
                $table->id(); // ID autoincremental (equivalente a INT PRIMARY KEY AUTO_INCREMENT)
                $table->unsignedBigInteger('id_estado')->default(1);
                $table->enum('tipo_pago', [
                    "Efectivo", 
                    "Nequi", 
                    "Daviplata", 
                    "Tarjeta", 
                    "Otro medio de pago"
                    ])->nullable(false);
                $table->timestamp('fecha_creacion')->default(DB::raw('CURRENT_TIMESTAMP'));
                $table->timestamp('fecha_modificacion')->default(DB::raw('CURRENT_TIMESTAMP'))->useCurrentOnUpdate();
                
                // Si necesitas una relación con otra tabla, por ejemplo, estados:
                $table->foreign('id_estado')->references('id')->on('estados')->onDelete('cascade');
            });

            // Insertar datos iniciales
            DB::table('medios_pago')->insert([
            ['tipo_pago' => 'Efectivo'],
            ['tipo_pago' => 'Nequi'],
            ['tipo_pago' => 'Daviplata'],
            ['tipo_pago' => 'Tarjeta'],
            ['tipo_pago' => 'Otro medio de pago'],
        ]);
    }

    
    public function down(): void
    {
        Schema::dropIfExists('medios_pago');
    }
    }
