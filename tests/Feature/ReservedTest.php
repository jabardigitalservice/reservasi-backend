<?php

namespace Tests\Feature;

use App\Models\Asset;
use App\Models\Reservation;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ReservedTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    /**
     * [testIndexPage render asset page]
     * @return void
     */
    public function testIndexReserved()
    {
        // 1. Create mock
        $admin = factory(User::class)->create(['role' => 'admin_reservasi']);
        $employee = factory(User::class)->create(['role' => 'employee']);
        $asset = factory(Asset::class)->create();

        $reservation = factory(Reservation::class)->create([
            'title' => 'Jabar Command Center',
            'description' => 'Study Tour',
            'asset_id' => $asset->id,
            'date' => '2021-01-22',
            'start_time' => '2021-01-22 07:30',
            'end_time' => '2021-01-22 10:00',
            'user_id_reservation' => $employee->id,
            'user_fullname' => $employee->name,
            'username' => $employee->username,
            'email' => $employee->email,
            'asset_name' => $asset->name,
            'asset_description' => $asset->description
        ]);

        $data = [
            'asset_id' => $asset->id,
            'date' => '2021-02-26'
        ];

        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->json('GET', config('app.url') . '/api/reserved', $data);

        // 3. Verify and Assertion
        $response->assertStatus(200);
    }

    public function testUpdateReserved()
    {
        // 1. Create mock
        $admin = factory(User::class)->create(['role' => 'admin_reservasi,./']);
        $employee = factory(User::class)->create(['role' => 'employee_reservasi']);
        $asset = factory(Asset::class)->create();
        $reservation = factory(Reservation::class)->create([
            'user_id_reservation' => $employee->id,
            'user_fullname' => $employee->name,
            'username' => $employee->username,
            'asset_id' => $asset->id,
            'asset_name' => $asset->name,
        ]);

        $data = [
            'note' => 'Oke',
            'approval_status' => 'already_approved',
            'approval_date' => '2021-01-28',
            'user_id_updated' => $admin->id,
        ];

        // 2. Hit Api Endpoint
        $response = $this->actingAs($admin)->put(route('reserved.update', $reservation), $data);
        // 3. Verify and Assertion
        $response->assertStatus(403);
    }
}
