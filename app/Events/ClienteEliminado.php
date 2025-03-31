<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;

class ClienteEliminado implements ShouldBroadcastNow
{
    use InteractsWithSockets, SerializesModels;

    public $clienteId;

    public function __construct($clienteId)
    {
        $this->clienteId = $clienteId;
    }

    public function broadcastOn()
    {
        return new Channel('clientes');
    }

    public function broadcastAs()
    {
        return 'cliente.eliminado';
    }
}

