<?php

namespace Tests\Feature;

use App\Models\Reservation;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;
    private $user;

    public function setUp() :void
    {
        parent::setUp();
        $this->artisan('db:seed');

        // 1. Create users
        $this->user = User::find(1);
        // 2. Create reservation
        $this->reservation = Reservation::find(1);
    }

    /**
     * [testIndexPage render asset page]
     * @return void
     */
    public function testIndexReservation()
    {
        // 1. Create mock
        $admin = factory(User::class)->create();
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('reservation.index'));
        // 3. Verify and Assertion
        $response->assertStatus(401);
    }

    public function testShowReservation()
    {
        // 1. Create mock
        $admin = factory(User::class)->create();
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('reservation.show', 1));
        // 3. Verify and Assertion
        $response->assertStatus(401);
    }

    public function testStoreReservation()
    {
        // 1. Create mock
        $admin = factory(User::class)->create();

        $data = [
            'title' => 'Study Tour',
            'description' => 'study tour ke jabar command center',
            'asset_id' => '1',
            'date' => '2021-01-22',
            'start_time' => '07:30',
            'end_time' => '10:00'
        ];

        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('reservation.store'), $data);
        // 3. Verify and Assertion
        $response->assertStatus(401);
    }

    public function testDestroyReservation()
    {
        // 1. Create mock
        $admin = factory(User::class)->create();
        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->get(route('reservation.destroy', 1));
        // 3. Verify and Assertion
        $response->assertStatus(401);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
