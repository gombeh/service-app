<?php

namespace App\Services;

class Abiliy
{
    public static function permissions($user): array
    {
        $user->load('roles.permissions');

        if($user->roles->contains('name', 'superAdmin')) {
            return ['*'];
        }
        return $user->roles->flatMap(function($role){
            return $role->permissions->pluck('name');
        })->unique()->toArray();
    }
}
