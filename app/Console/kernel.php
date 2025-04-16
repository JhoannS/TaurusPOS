<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Registra los comandos de Artisan disponibles.
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }

    /**
     * Define la programación de comandos.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('membresias:reducir-dias')->daily(); // o cada minuto para pruebas: ->everyMinute()
    }


}
