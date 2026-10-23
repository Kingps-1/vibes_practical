<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Room;
use App\Models\Student;
use App\Models\RoomAllocation;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HostelManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Test public catalog and room search.
     */
    public function test_guest_can_view_rooms_catalog_and_filter(): void
    {
        $response = $this->get('/rooms');
        $response->assertStatus(200);
        $response->assertSee('Hostel Rooms Catalog');

        // Test search filter
        $searchResponse = $this->get('/rooms?search=A-101');
        $searchResponse->assertStatus(200);
        $searchResponse->assertSee('A-101');
    }

    /**
     * Test guest redirection for protected CRUD routes.
     */
    public function test_guest_is_redirected_to_login_for_protected_actions(): void
    {
        $response = $this->get('/rooms/create');
        $response->assertRedirect('/login');

        $allocResponse = $this->get('/allocations');
        $allocResponse->assertRedirect('/login');

        $reportsResponse = $this->get('/reports');
        $reportsResponse->assertRedirect('/login');
    }

    /**
     * Test authentication (login & logout).
     */
    public function test_user_can_login_and_access_dashboard(): void
    {
        $admin = User::where('email', 'admin@hostel.edu')->first();
        $this->assertNotNull($admin);

        $response = $this->post('/login', [
            'email' => 'admin@hostel.edu',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($admin);

        // Access dashboard
        $dashboardResponse = $this->actingAs($admin)->get('/dashboard');
        $dashboardResponse->assertStatus(200);
        $dashboardResponse->assertSee('Hostel Management Dashboard');
        $dashboardResponse->assertSee('Total Rooms');
    }

    /**
     * Test Room CRUD Module.
     */
    public function test_authenticated_user_can_create_and_view_room(): void
    {
        $admin = User::where('email', 'admin@hostel.edu')->first();

        $roomNumber = 'TEST-' . rand(100, 999);
        $response = $this->actingAs($admin)->post('/rooms', [
            'room_number' => $roomNumber,
            'block' => 'Block A (Boys)',
            'room_type' => 'Double Sharing',
            'capacity' => 2,
            'floor' => 1,
            'monthly_fee' => 4500,
            'status' => 'available',
            'amenities' => 'Wi-Fi, Study Table',
            'description' => 'Test Room Entry',
        ]);

        $room = Room::where('room_number', $roomNumber)->first();
        $this->assertNotNull($room);
        $response->assertRedirect(route('rooms.show', $room));

        // View room details
        $viewResponse = $this->get(route('rooms.show', $room));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee($roomNumber);
    }

    /**
     * Test Allocation CRUD Module and automatic occupancy sync.
     */
    public function test_room_allocation_workflow_and_bed_release(): void
    {
        $admin = User::where('email', 'admin@hostel.edu')->first();

        // Create a test room and student
        $room = Room::create([
            'room_number' => 'ALLOC-' . rand(100, 999),
            'block' => 'Block A (Boys)',
            'room_type' => 'Double Sharing',
            'capacity' => 2,
            'current_occupancy' => 0,
            'floor' => 1,
            'monthly_fee' => 5000,
            'status' => 'available',
        ]);

        $student = Student::create([
            'student_id_number' => 'TEST-STU-' . rand(1000, 9999),
            'full_name' => 'Karan Sharma',
            'gender' => 'Male',
            'email' => 'karan.' . rand(100, 999) . '@hostel.edu',
            'phone' => '+91 99999 88888',
            'course' => 'B.Tech IT',
            'semester' => 2,
            'guardian_name' => 'Suresh Sharma',
            'guardian_phone' => '+91 99999 77777',
        ]);

        // Allocate room
        $allocResponse = $this->actingAs($admin)->post('/allocations', [
            'room_id' => $room->id,
            'student_id' => $student->id,
            'bed_number' => 'Bed 1',
            'allocation_date' => now()->toDateString(),
            'fee_status' => 'paid',
            'remarks' => 'Test Allotment',
        ]);

        $allocation = RoomAllocation::where('room_id', $room->id)->where('student_id', $student->id)->first();
        $this->assertNotNull($allocation);
        $allocResponse->assertRedirect(route('allocations.show', $allocation));

        // Check room occupancy increased
        $room->refresh();
        $this->assertEquals(1, $room->current_occupancy);

        // Vacate student
        $vacateResponse = $this->actingAs($admin)->post(route('allocations.vacate', $allocation));
        $vacateResponse->assertRedirect(route('allocations.index'));

        // Check room occupancy decreased back to 0
        $room->refresh();
        $this->assertEquals(0, $room->current_occupancy);
        $this->assertEquals('available', $room->status);
    }

    /**
     * Test Report and Summary View module.
     */
    public function test_occupancy_report_lists_records(): void
    {
        $admin = User::where('email', 'admin@hostel.edu')->first();

        $response = $this->actingAs($admin)->get('/reports');
        $response->assertStatus(200);
        $response->assertSee('Hostel Occupancy');
        $response->assertSee('Block-wise Capacity');
        $response->assertSee('Active Resident Allocations Master Listing');
    }
}
