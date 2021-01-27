<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class AssetTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    private $user;
    private $asset;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');

        // 1. Mock users
        $this->user = User::find(1);
        // 2. Mock asset
        $this->asset = Asset::find(1);
    }

    /**
     * [testIndex render asset]
     * @return void
     */
    public function testIndexAsset()
    {
        // 1. Mock user
        $admin = $this->user;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('asset.index'));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }

    /**
     * [testIndex render asset per page by 50]
     * @return void
     */
    public function testIndexAssetPerPage()
    {
        // 1. Mock user
        $admin = $this->user;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('asset.index', ['perPage' => 50]));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }

    /**
     * [testIndex render asset search by name]
     * @return void
     */
    public function testIndexAssetSearchByName()
    {
        // 1. Mock user
        $admin = $this->user;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('asset.index', ['name' => 'zoom']));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }

    /**
     * [testIndex render asset by status]
     * @return void
     */
    public function testIndexAssetSearchByStatus()
    {
        // 1. Mock user
        $admin = $this->user;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('asset.index', ['status' => 'active']));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }

    /**
     * [testShowAsset render asset by id]
     *
     * @return void
     */
    public function testShowAsset()
    {
        // 1. Create mock
        $admin = $this->user;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('asset.show', 1));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }

    /**
     * [testStoreAsset mock a store asset function]
     *
     * @return void
     */
    public function testStoreAsset()
    {
        // 1. Create mock
        $admin = $this->user;

        $data = [
            'name' => 'Jabar Command Center',
            'status' => 'active',
            'description' => 'JDS Team',
        ];

        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->post(route('asset.store'), $data);
        // 3. Verify and Assertion
        $response->assertStatus(201);
    }

    /**
     * [testStoreAsset mock a delete asset function]
     *
     * @return void
     */
    public function testDestroyAsset()
    {
        // 1. Create mock
        $admin = $this->user;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->delete(route('asset.destroy', 1));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }

    /**
     * [testStoreAsset render active list asset]
     *
     * @return void
     */
    public function testActiveListAsset()
    {
        // 1. Create mock
        $admin = $this->user;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('asset.list'));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }
}
