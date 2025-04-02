<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClienteTaurus; // Ajusta el modelo según tu tabla de clientes
use Illuminate\Support\Facades\Hash;

class EncryptPasswords extends Command
{
    protected $signature = 'encrypt:passwords';
    protected $description = 'Encripta todas las contraseñas que no están encriptadas en la base de datos';

    public function handle()
    {
        // Buscar usuarios con contraseñas sin encriptar
        $clientes = ClienteTaurus::whereRaw('CHAR_LENGTH(contrasenia_ct) < 60')->get(); 

        if ($clientes->isEmpty()) {
            $this->info('Todas las contraseñas ya están encriptadas.');
            return;
        }

        foreach ($clientes as $cliente) {
            $cliente->contrasenia_ct = Hash::make($cliente->contrasenia_ct);
            $cliente->save();
        }

        $this->info('Contraseñas encriptadas correctamente.');
    }
}
