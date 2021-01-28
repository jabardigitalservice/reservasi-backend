<?php

use App\Models\Asset;
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
        factory(Asset::class, 5)->create()->each(function ($asset) {
            $asset->save();
        });
    }
}
