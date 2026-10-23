<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use App\Models\Student;
use App\Models\RoomAllocation;
use App\Models\MaintenanceRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Default Users (Admin, Warden, Student)
        $admin = User::firstOrCreate(
            ['email' => 'admin@hostel.edu'],
            [
                'name' => 'Dr. Rajesh Sharma',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+91 98765 43210',
            ]
        );

        $warden = User::firstOrCreate(
            ['email' => 'warden@hostel.edu'],
            [
                'name' => 'Prof. Sunita Verma',
                'password' => Hash::make('password'),
                'role' => 'warden',
                'phone' => '+91 98765 43211',
            ]
        );

        $studentUser1 = User::firstOrCreate(
            ['email' => 'student@hostel.edu'],
            [
                'name' => 'Aarav Patel',
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone' => '+91 98765 11111',
            ]
        );

        $studentUser2 = User::firstOrCreate(
            ['email' => 'priya@hostel.edu'],
            [
                'name' => 'Priya Mehta',
                'password' => Hash::make('password'),
                'role' => 'student',
                'phone' => '+91 98765 22222',
            ]
        );

        // 2. Seed Hostel Rooms
        $roomsData = [
            [
                'room_number' => 'A-101',
                'block' => 'Block A (Boys)',
                'room_type' => 'Single Deluxe',
                'capacity' => 1,
                'monthly_fee' => 7500.00,
                'floor' => 1,
                'amenities' => 'Attached Bathroom, Air Conditioner, Study Desk, High-Speed Wi-Fi',
                'description' => 'Spacious single room facing the eastern garden.',
                'status' => 'available',
            ],
            [
                'room_number' => 'A-102',
                'block' => 'Block A (Boys)',
                'room_type' => 'Double Sharing',
                'capacity' => 2,
                'monthly_fee' => 5000.00,
                'floor' => 1,
                'amenities' => 'High-Speed Wi-Fi, Balcony, Dual Closets, Ceiling Fan',
                'description' => 'Bright double room with private balcony view.',
                'status' => 'available',
            ],
            [
                'room_number' => 'A-201',
                'block' => 'Block A (Boys)',
                'room_type' => 'Triple Sharing',
                'capacity' => 3,
                'monthly_fee' => 3800.00,
                'floor' => 2,
                'amenities' => 'Wi-Fi, 3 Wardrobes, Study Lamp, Attached Washroom',
                'description' => 'Budget friendly triple room on the second floor.',
                'status' => 'available',
            ],
            [
                'room_number' => 'B-101',
                'block' => 'Block B (Girls)',
                'room_type' => 'Single Deluxe',
                'capacity' => 1,
                'monthly_fee' => 8000.00,
                'floor' => 1,
                'amenities' => 'AC, Attached Bath, Biometric Entry, Book Shelves, Wi-Fi',
                'description' => 'Premium quiet single suite for final year research scholars.',
                'status' => 'available',
            ],
            [
                'room_number' => 'B-102',
                'block' => 'Block B (Girls)',
                'room_type' => 'Double Sharing',
                'capacity' => 2,
                'monthly_fee' => 5200.00,
                'floor' => 1,
                'amenities' => 'Wi-Fi, Attached Washroom, Separate Study Desks',
                'description' => 'Comfortable double occupancy room with ample natural light.',
                'status' => 'available',
            ],
            [
                'room_number' => 'B-203',
                'block' => 'Block B (Girls)',
                'room_type' => 'Double Sharing',
                'capacity' => 2,
                'monthly_fee' => 5200.00,
                'floor' => 2,
                'amenities' => 'Wi-Fi, Attached Balcony, Geyser, Study Table',
                'description' => 'Quiet second floor double sharing room.',
                'status' => 'available',
            ],
            [
                'room_number' => 'C-101',
                'block' => 'Block C (Scholars)',
                'room_type' => 'Single Deluxe',
                'capacity' => 1,
                'monthly_fee' => 9000.00,
                'floor' => 1,
                'amenities' => 'Central AC, High-Speed LAN, Refrigerator, Ergonomic Chair',
                'description' => 'International and postgraduate scholars private studio room.',
                'status' => 'available',
            ],
            [
                'room_number' => 'C-201',
                'block' => 'Block C (Scholars)',
                'room_type' => 'Double Sharing',
                'capacity' => 2,
                'monthly_fee' => 6000.00,
                'floor' => 2,
                'amenities' => 'High-Speed Wi-Fi, Balcony, Attached Bath',
                'description' => 'Deluxe twin room for postgraduates.',
                'status' => 'maintenance',
            ],
        ];

        $rooms = [];
        foreach ($roomsData as $r) {
            $rooms[$r['room_number']] = Room::firstOrCreate(['room_number' => $r['room_number']], $r);
        }

        // 3. Seed Students
        $studentsData = [
            [
                'user_id' => $studentUser1->id,
                'student_id_number' => 'STU-2026-001',
                'full_name' => 'Aarav Patel',
                'gender' => 'Male',
                'email' => 'student@hostel.edu',
                'phone' => '+91 98765 11111',
                'course' => 'B.Tech Computer Science',
                'semester' => 4,
                'guardian_name' => 'Dinesh Patel',
                'guardian_phone' => '+91 98220 54321',
                'address' => '42 Green Avenue, Ahmedabad, Gujarat',
                'blood_group' => 'B+',
            ],
            [
                'user_id' => $studentUser2->id,
                'student_id_number' => 'STU-2026-002',
                'full_name' => 'Priya Mehta',
                'gender' => 'Female',
                'email' => 'priya@hostel.edu',
                'phone' => '+91 98765 22222',
                'course' => 'BCA Information Tech',
                'semester' => 2,
                'guardian_name' => 'Ramesh Mehta',
                'guardian_phone' => '+91 98220 78901',
                'address' => '12 Orchid Heights, Surat, Gujarat',
                'blood_group' => 'O+',
            ],
            [
                'user_id' => null,
                'student_id_number' => 'STU-2026-003',
                'full_name' => 'Rohan Joshi',
                'gender' => 'Male',
                'email' => 'rohan.j@college.edu',
                'phone' => '+91 98765 33333',
                'course' => 'MCA Software Engg',
                'semester' => 3,
                'guardian_name' => 'Mahesh Joshi',
                'guardian_phone' => '+91 98220 33344',
                'address' => '88 Shanti Nagar, Rajkot, Gujarat',
                'blood_group' => 'A+',
            ],
            [
                'user_id' => null,
                'student_id_number' => 'STU-2026-004',
                'full_name' => 'Ananya Iyer',
                'gender' => 'Female',
                'email' => 'ananya.iyer@college.edu',
                'phone' => '+91 98765 44444',
                'course' => 'MBA Finance',
                'semester' => 1,
                'guardian_name' => 'Subramanian Iyer',
                'guardian_phone' => '+91 98220 55566',
                'address' => '104 Palm Grove, Vadodara, Gujarat',
                'blood_group' => 'AB+',
            ],
            [
                'user_id' => null,
                'student_id_number' => 'STU-2026-005',
                'full_name' => 'Kabir Sen',
                'gender' => 'Male',
                'email' => 'kabir.sen@college.edu',
                'phone' => '+91 98765 55555',
                'course' => 'B.Tech Mechanical',
                'semester' => 6,
                'guardian_name' => 'Alok Sen',
                'guardian_phone' => '+91 98220 77788',
                'address' => '54 Sunrise Enclave, Gandhinagar, Gujarat',
                'blood_group' => 'O-',
            ],
        ];

        $students = [];
        foreach ($studentsData as $s) {
            $students[$s['student_id_number']] = Student::firstOrCreate(
                ['student_id_number' => $s['student_id_number']],
                $s
            );
        }

        // 4. Seed Allocations
        $allocations = [
            [
                'room_id' => $rooms['A-101']->id,
                'student_id' => $students['STU-2026-001']->id,
                'allocated_by' => $admin->id,
                'bed_number' => 'Bed 1',
                'allocation_date' => now()->subMonths(2)->format('Y-m-d'),
                'status' => 'active',
                'fee_status' => 'paid',
                'remarks' => 'Single occupancy allotment. Semester fee fully paid.',
            ],
            [
                'room_id' => $rooms['A-102']->id,
                'student_id' => $students['STU-2026-003']->id,
                'allocated_by' => $warden->id,
                'bed_number' => 'Bed 1',
                'allocation_date' => now()->subMonth()->format('Y-m-d'),
                'status' => 'active',
                'fee_status' => 'paid',
                'remarks' => 'Double sharing allotment.',
            ],
            [
                'room_id' => $rooms['B-101']->id,
                'student_id' => $students['STU-2026-002']->id,
                'allocated_by' => $warden->id,
                'bed_number' => 'Bed 1',
                'allocation_date' => now()->subMonths(3)->format('Y-m-d'),
                'status' => 'active',
                'fee_status' => 'paid',
                'remarks' => 'Girls Hostel Single Suite allotment.',
            ],
            [
                'room_id' => $rooms['B-102']->id,
                'student_id' => $students['STU-2026-004']->id,
                'allocated_by' => $warden->id,
                'bed_number' => 'Bed 1',
                'allocation_date' => now()->subWeeks(2)->format('Y-m-d'),
                'status' => 'active',
                'fee_status' => 'pending',
                'remarks' => 'Bed 1 allotted. Fee payment due by month end.',
            ],
        ];

        foreach ($allocations as $alloc) {
            RoomAllocation::firstOrCreate(
                [
                    'room_id' => $alloc['room_id'],
                    'student_id' => $alloc['student_id'],
                ],
                $alloc
            );
        }

        // Sync occupancy for all rooms
        foreach (Room::all() as $room) {
            $room->syncOccupancy();
        }

        // 5. Seed Maintenance Requests
        MaintenanceRequest::firstOrCreate(
            [
                'room_id' => $rooms['A-101']->id,
                'student_id' => $students['STU-2026-001']->id,
                'title' => 'Air Conditioner filter cleaning needed',
            ],
            [
                'description' => 'The AC is cooling slowly and requires routine filter cleaning and thermostat check.',
                'priority' => 'low',
                'status' => 'in_progress',
                'reported_at' => now()->subDays(3),
            ]
        );

        MaintenanceRequest::firstOrCreate(
            [
                'room_id' => $rooms['B-101']->id,
                'student_id' => $students['STU-2026-002']->id,
                'title' => 'Bathroom Geyser power socket loose',
            ],
            [
                'description' => 'The socket switch sparks intermittently when turned on. Electrician required.',
                'priority' => 'urgent',
                'status' => 'reported',
                'reported_at' => now()->subHours(8),
            ]
        );
    }
}
