<?php

namespace App\Console\Commands;

use App\Models\ClienteTaurus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class EncryptPasswords extends Command
{
    protected $signature = 'encrypt:passwords';
    protected $description = 'Encripta las contraseñas existentes en clientes_taurus';

    public function handle()
    {
        $clientes = ClienteTaurus::all();

        foreach ($clientes as $cliente) {
            if (!Hash::needsRehash($cliente->contrasenia_ct)) {
                continue;
            }

            $cliente->contrasenia_ct = Hash::make($cliente->contrasenia_ct);
            $cliente->save();
        }

        $this->info('Contraseñas encriptadas correctamente.');
    }
}
