<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');

        // Provide mocking data for testing
        $this->user = factory(User::class)->create();
    }

    /**
     * [testIndexPage render asset page]
     * @return void
     */
    public function testIndexProfile()
    {
        // 1. Create mock
        $user = $this->user;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($user)->get(route('user.get'));
        // 3. Verify and Assertion
        $response->assertStatus(200);
        $response->assertJson(['data' => ['name' => $user->name]]);
    }
}
