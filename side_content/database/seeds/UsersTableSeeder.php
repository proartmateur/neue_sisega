<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        $users = array(
            array(
                'name' => 'Administrador',
                'email' => 'admin@admin.com',
                'password' => bcrypt('123456789'),
                'stall' => 'Administrador',
                'role' => '1',
                'status' => '1'
            )
        );

        DB::table('users')->insert($users);
    }
}
