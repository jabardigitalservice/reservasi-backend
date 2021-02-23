<?php

namespace Tests\Feature;

use App\Mail\ReservationStoreMail;
use App\Models\Asset;
use App\Models\Reservation;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
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
        $this->employee = factory(User::class)->create([
            'role' => 'employee_reservasi',
        ]);
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
        Mail::fake();
        // 1. Mocking data
        $employee = $this->employee;
        $reservation = factory(Reservation::class)->create([
            'user_id_reservation' => $employee->uuid,
            'user_fullname' => $employee->name,
            'username' => $employee->username,
            'asset_id' => $this->asset->id,
            'asset_name' => $this->asset->name,
            'approval_status' => 'already_approved',
        ]);

        // 2. Hit Api Endpoint
        $response = $this->actingAs($employee)->get(route('reservation.show', $reservation->id));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }

    public function testStoreReservation()
    {
        Mail::fake();
        // 1. Mocking data
        $employee = $this->employee;
        $data = [
            'title' => 'test',
            'description' => 'testing phpunit',
            'asset_id' => $this->asset->id,
            'date' => Carbon::now('+07:00')->format('Y-m-d'),
            'start_time' => Carbon::now('+07:00')->format('Y-m-d H:i'),
            'end_time' => Carbon::now('+07:00')->addMinutes(30)->format('Y-m-d H:i'),
            'approval_status' => 'already_approved',
            'user_id_reservation' => $employee->uuid,
            'user_fullname' => $employee->name,
            'username' => $employee->username,
            'asset_id' => $this->asset->id,
            'asset_name' => $this->asset->name,
            'approval_status' => 'already_approved',
            'join_url' => 'https://localhost:3000',
        ];
        // 2. Hit Api Endpoint
        $response = $this->actingAs($employee)->post(route('reservation.store'), $data);
        // 3. Verify and Assertion
        $response->assertStatus(201);
        $response->assertJson(['data' => [
            'title' => $data['title'],
        ]]);
    }

    public function testSendEmailReservation()
    {
        Mail::fake();
        $employee = $this->employee;

        $reservation = factory(Reservation::class)->create([
            'user_id_reservation' => $employee->uuid,
            'user_fullname' => $employee->name,
            'username' => $employee->username,
            'asset_id' => $this->asset->id,
            'asset_name' => $this->asset->name,
            'approval_status' => 'already_approved',
        ]);

        Mail::to($employee)->send(new ReservationStoreMail($reservation, 'message'));

        Mail::assertSent(ReservationStoreMail::class);
    }

    public function testDestroyReservation()
    {
        Mail::fake();
        // 1. Mocking data
        $employee = $this->employee;
        $asset = factory(Asset::class)->create();
        $reservation = factory(Reservation::class)->create([
            'user_id_reservation' => $employee->uuid,
            'user_fullname' => $employee->name,
            'username' => $employee->username,
            'asset_id' => $asset->id,
            'asset_name' => $asset->name,
        ]);

        // 2. Hit Api Endpoint
        $response = $this->actingAs($employee)->delete(route('reservation.destroy', $reservation->id));
        // 3. Verify and Assertion
        $response->assertStatus(200);
    }
}
