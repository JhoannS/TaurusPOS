<?php

namespace App\Policies;

use App\Models\ClienteTaurus;

class RolePolicy
{
    /**
     * Permite el acceso si el rol coincide
     */
    public function accessRole(ClienteTaurus $user, $role)
    {
        return $user->id_rol == $role;
    }
}
