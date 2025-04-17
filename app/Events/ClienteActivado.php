<?php
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ClienteActivado implements ShouldBroadcast
{
    public $cliente;

    public function __construct($cliente)
    {
        $this->cliente = $cliente;
    }

    public function broadcastOn()
    {
        return new Channel('clientes');
    }
}
