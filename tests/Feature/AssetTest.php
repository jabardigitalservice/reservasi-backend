<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\User;
use App\Models\Asset;

class AssetTest extends TestCase
{
     use RefreshDatabase;
     private $user;
     private $asset;

     public function setUp() :void
     {
         parent::setUp();
         $this->artisan('db:seed');

         // 1. Create users
         $this->user = User::find(1);
         // 2. Create asset
         $this->asset = Asset::find(1);
     }

    /**
     * [testIndexPage render asset page]
     * @return void
     */
    public function testIndexAsset()
    {
        // 1. Create mock
        $admin = factory(User::class)->create();
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('asset.index'));
        // 3. Verify and Assertion
        $response->assertStatus(401);
    }

    public function testShowAsset()
    {
        // 1. Create mock
        $admin = factory(User::class)->create();
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('asset.show', 1));
        // 3. Verify and Assertion
        $response->assertStatus(401);
    }

    public function testStoreAsset()
    {
        // 1. Create mock
        $admin = factory(User::class)->create();

        $data = [
            'name' => 'Jabar Command Center',
            'status' => 'active',
            'description' => 'JDS Team'
        ];

        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('asset.store'), $data);
        // 3. Verify and Assertion
        $response->assertStatus(401);
    }

    public function testDestroyAsset()
    {
        // 1. Create mock
        $admin = factory(User::class)->create();
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('asset.destroy', 1));
        // 3. Verify and Assertion
        $response->assertStatus(401);
    }

    public function testActiveListAsset()
    {
        // 1. Create mock
        $admin = factory(User::class)->create();
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('asset.list'));
        // 3. Verify and Assertion
        $response->assertStatus(401);
    }
}
