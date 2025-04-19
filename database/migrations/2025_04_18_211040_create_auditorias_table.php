<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('auditorias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('clientes_taurus')->nullOnDelete();
            $table->string('accion'); // create, update, delete, login, etc.
            $table->string('modelo')->nullable(); // Modelo afectado, ej: "Producto"
            $table->string('modelo_id')->nullable(); // ID del modelo afectado
            $table->text('comentario')->nullable(); // Descripción personalizada
            $table->string('ip')->nullable();
            $table->string('navegador')->nullable();
            $table->string('plataforma')->nullable();
            $table->string('url')->nullable();
            $table->json('cambios')->nullable(); // Old vs New values
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditorias');
    }
};
