<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateTipoDocumentosTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipo_documentos', function (Blueprint $table) {
            $table->id();
            $table->enum('documento_legal', [
                'Cedula ciudadania',
                'Tarjeta identidad',
                'Cedula extranjeria',
                'Pasaporte'
            ])->nullable(false);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();
        });

        // Insertar datos iniciales
        DB::table('tipo_documentos')->insert([
            ['documento_legal' => 'Cedula ciudadania'],
            ['documento_legal' => 'Tarjeta identidad'],
            ['documento_legal' => 'Cedula extranjeria'],
            ['documento_legal' => 'Pasaporte'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_documentos');
    }
}

// <?php

// use Illuminate\Database\Migrations\Migration;
// use Illuminate\Database\Schema\Blueprint;
// use Illuminate\Support\Facades\Schema;

// return new class extends Migration
// {
//     /**
//      * Run the migrations.
//      */
//     public function up(): void
// {
//     Schema::create('clientes', function (Blueprint $table) {
//         // Columna primary key auto incremental
//         $table->id();
        
//         // Claves foráneas (se asume que las tablas 'estados' y 'tipo_documentos' existen)
//         $table->unsignedBigInteger('id_estado')->default(1);
//         $table->unsignedBigInteger('id_tipo_documento');

//         // Campos de información del cliente
//         $table->string('nombres_cliente', 100);
//         $table->string('apellidos_cliente', 250);
//         $table->string('numero_documento_cliente', 30);
//         $table->string('email_cliente', 100)->unique();
//         $table->string('telefono_cliente', 12)->unique();
//         $table->string('direccion_cliente', 100);
//         $table->string('barrio_cliente', 30);

//         // Campos de control de tiempo
//         $table->timestamp('fecha_creacion')->useCurrent();
//         $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();

//         // Definición de claves foráneas
//         $table->foreign('id_estado')->references('id')->on('estados');
//         $table->foreign('id_tipo_documento')->references('id')->on('tipo_documentos');
//     });
// }


//     /**
//      * Reverse the migrations.
//      */
//     public function down(): void
//     {
//         Schema::dropIfExists('clientes');
//     }
// };
