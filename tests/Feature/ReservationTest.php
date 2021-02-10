<?php

namespace Tests\Feature;

use App\Mail\ReservationStoreMail;
use App\Models\Asset;
use App\Models\Reservation;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;
    use WithoutMiddleware;

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');

        // Provide mocking data for testing
        $this->asset = factory(Asset::class)->create();
        $this->employee = factory(User::class)->create(['role' => 'employee_reservasi']);
    }

    /**
     * [testIndexPage render asset page]
     * @return void
     */
    public function testIndexReservation()
    {
        // 1. Mocking data
        $employee = $this->employee;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($employee)->get(route('reservation.index'));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }

    public function testIndexReservationSearchTitle()
    {
        // 1. Mocking data
        $employee = $this->employee;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($employee)->get(route('reservation.index', ['search' => 'jabar']));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }

    public function testIndexReservationPerPage()
    {
        // 1. Mocking data
        $employee = $this->employee;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($employee)->get(route('reservation.index', ['perPage' => 50]));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }

    public function testIndexReservationFilterByAsset()
    {
        // 1. Mocking data
        $employee = $this->employee;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($employee)->get(route('reservation.index', ['asset_id' => 1]));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }

    public function testIndexReservationFilterByApprovalStatus()
    {
        // 1. Mocking data
        $employee = $this->employee;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($employee)->get(route('reservation.index', ['approval_status' => 'already_approved']));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }

    public function testIndexReservationFilterByStartDate()
    {
        // 1. Mocking data
        $employee = $this->employee;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($employee)->get(route('reservation.index', ['start_date' => '2021-01-27']));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }

    public function testIndexReservationFilterByEndDate()
    {
        // 1. Mocking data
        $employee = $this->employee;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($employee)->get(route('reservation.index', ['end_date' => '2021-01-28']));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }

    public function testIndexReservationSortedBy()
    {
        // 1. Mocking data
        $employee = $this->employee;
        // 2. Hit Api Endpoint
        $response = $this->actingAs($employee)->get(route('reservation.index', [
            'sortBy' => 'reservation_time',
            'date' => '2021-01-25',
            'start_time' => '07:00',
            'end_time' => '09:00',
        ]));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }

    public function testShowReservation()
    {
        // 1. Mocking data
        $employee = $this->employee;
        $reservation = factory(Reservation::class)->create([
            'user_id_reservation' => $employee->id,
            'user_fullname' => $employee->name,
            'username' => $employee->username,
            'asset_id' => $this->asset->id,
            'asset_name' => $this->asset->name,
        ]);
        // 2. Hit Api Endpoint
        $response = $this->actingAs($employee)->get(route('reservation.show', $reservation));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }

    public function testStoreReservation()
    {
        // 1. Mocking data
        Notification::fake();
        $employee = $this->employee;

        $data = [
            'title' => 'Jabar Command Center',
            'description' => 'Study Tour',
            'asset_id' => $this->asset->id,
            'date' => '2021-01-22',
            'start_time' => '2021-01-22 07:30',
            'end_time' => '2021-01-22 10:00',
            'user_id_reservation' => $employee->id,
            'user_fullname' => $employee->name,
            'username' => $employee->username,
            'email' => $employee->email,
            'asset_name' => $this->asset->name,
            'asset_description' => $this->asset->description
        ];

        // 2. Hit Api Endpoint
        $response = $this->actingAs($employee)->post(route('reservation.store'), $data);

        // 3. Verify and Assertion
        Notification::assertNotSentTo(
            $employee,
            ReservationStoreMail::class,
            function ($channels) {
                return in_array('mail', $channels);
            }
        );
        $response->assertStatus(201);
        $response->assertJson(['data' => [
            'title' => $data['title'],
        ]]);
    }

    public function testDestroyReservation()
    {
        // 1. Mocking data
        $employee = $this->employee;
        $asset = factory(Asset::class)->create();
        $reservation = factory(Reservation::class)->create([
            'user_id_reservation' => $employee->id,
            'user_fullname' => $employee->name,
            'username' => $employee->username,
            'asset_id' => $asset->id,
            'asset_name' => $asset->name,
        ]);

        // 2. Hit Api Endpoint
        $this->expectException('Symfony\Component\HttpKernel\Exception\HttpException');
        $this->withoutExceptionHandling();
        $response = $this->actingAs($employee)->delete(route('reservation.destroy', $reservation->id));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }
}
