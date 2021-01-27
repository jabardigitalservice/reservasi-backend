<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();

        \DB::table('users')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Admin Reservasi Digiteam',
                'username' => 'admin@reservationdigiteam.com',
                'password' => bcrypt('admin@reservationdigiteam.com'),
                'email' => 'admin@reservationdigiteam.com',
                'role' => 'admin_reservasi,./',
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Employee Account',
                'username' => 'employee@reservationdigiteam.com',
                'password' => bcrypt('employee@reservationdigiteam.com'),
                'email' => 'employee@reservationdigiteam.com',
                'role' => 'employee_reservasi',
            ),
        ));
    }
}
