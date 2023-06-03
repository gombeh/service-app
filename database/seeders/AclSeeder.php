<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AclSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::insert([
            ['name' => 'superAdmin'],
            ['name' => 'admin'],
            ['name' => 'user'],
        ]);

        Permission::insert([
            ['name' => 'home'],
            ['name' => 'service:index'],
            ['name' => 'service:create'],
            ['name' => 'service:update-status'],
            ['name' => 'service:update-status-socket'],
        ]);

        Role::findOrFail(2)->permissions()->sync([1,2,3,4, 5]);
        Role::findOrFail(3)->permissions()->sync([1]);

        $superAdmin = User::first();
        $superAdmin->roles()->sync(1);

        $users = User::where('id', '<>', 1)->get();
        $users->map(function($user){
            $user->roles()->sync(mt_rand(2, 3));
        });
    }
}
