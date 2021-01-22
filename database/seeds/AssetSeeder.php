<?php

use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('assets')->delete();

        \DB::table('assets')->insert(array (
            0 =>
            array (
                'id' => 1,
                'name' => 'Zoom Meeting',
                'status' => 'active',
                'description' => 'Resources of Digital Room',
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => '2018-05-23 18:33:57',
            ),
            1 =>
            array (
                'id' => 2,
                'name' => 'Google Meet',
                'status' => 'not_active',
                'description' => 'Resources of Digital Room',
                'deleted_at' => NULL,
                'created_at' => NULL,
                'updated_at' => '2018-05-23 18:33:57',
            ),
        ));
    }
}
