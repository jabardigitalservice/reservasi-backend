<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use WithoutMiddleware;

    private $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');

        // Provide mocking data for testing
        $this->admin = factory(User::class)->create(['role' => 'admin_reservasi,./']);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testReservationStatistic()
    {
        // 1. Mock user
        $admin = $this->admin;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('reservation.dashboard'));
        // 3. Verify and Assertion
        $response->assertStatus(200);
        $response->assertJson(['not_yet_approved' => 0]);
    }
}
