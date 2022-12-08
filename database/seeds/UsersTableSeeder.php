<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // select * from roles where nombre = 'admin'
        $admin = Role::where('nombre', 'admin')->first();
        $repartidor = Role::where('nombre', 'repartidor')->first();

        $user = new User;
        $user->name = 'admin';
        $user->email = 'admin@gmail.com';
        $user->password = bcrypt('administrador123');
        $user->save();
        $user->roles()->attach($admin);

        $user = new User;
        $user->name = 'r1';
        $user->email = 'repartidor@gmail.com';
        $user->password = bcrypt('repartidor123');
        $user->save();
        $user->roles()->attach($repartidor);

    }
}
