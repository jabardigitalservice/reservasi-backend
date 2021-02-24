<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\Response;
use Tests\TestCase;

class AssetTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');

        // Provide mocking data for testing
        $this->asset = factory(Asset::class)->create();
        $this->admin = factory(User::class)->create(['role' => 'admin_reservasi,./']);
    }

    /**
     * [testIndex render asset]
     * @return void
     */
    public function testIndexAsset()
    {
        // 1. Mock data
        $admin = $this->admin;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('asset.index'));
        // 3. Verify and Assertion
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * [testIndex render asset per page by 50]
     * @return void
     */
    public function testIndexAssetPerPage()
    {
        // 1. Mock data
        $admin = $this->admin;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('asset.index', ['perPage' => 50]));
        // 3. Verify and Assertion
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * [testIndex render asset search by name]
     * @return void
     */
    public function testIndexAssetSearchByName()
    {
        // 1. Mock data
        $admin = $this->admin;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('asset.index', ['name' => 'zoom']));
        // 3. Verify and Assertion
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * [testIndex render asset by status]
     * @return void
     */
    public function testIndexAssetSearchByStatus()
    {
        // 1. Mock data
        $admin = $this->admin;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('asset.index', ['status' => 'active']));
        // 3. Verify and Assertion
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * [testShowAsset render asset by id]
     *
     * @return void
     */
    public function testShowAsset()
    {
        // 1. Create mock
        $admin = $this->admin;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('asset.show', $this->asset));
        // 3. Verify and Assertion
        $response->assertStatus(Response::HTTP_OK);
    }

    /**
     * [testStoreAsset mock a store asset function]
     *
     * @return void
     */
    public function testStoreAsset()
    {
        // 1. Create mock
        $admin = $this->admin;

        $data = [
            'name' => 'Jabar Command Center',
            'status' => 'active',
            'description' => 'JDS Team',
            'capacity' => 100
        ];

        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->post(route('asset.store'), $data);
        // 3. Verify and Assertion
        $response->assertStatus(Response::HTTP_CREATED);
    }

    /**
     * [testStoreAsset mock a delete asset function]
     *
     * @return void
     */
    public function testDestroyAsset()
    {
        // 1. Create mock
        $admin = $this->admin;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->delete(route('asset.destroy', $this->asset));
        // 3. Verify and Assertion
        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /**
     * [testStoreAsset render active list asset]
     *
     * @return void
     */
    public function testActiveListAsset()
    {
        // 1. Create mock
        $admin = $this->admin;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('asset.list'));
        // 3. Verify and Assertion
        $response->assertStatus(Response::HTTP_OK);
    }
}
