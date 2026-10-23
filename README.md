# Program No. 29: MAJOR PROJECT – I
## Hostel Room Allocation System (HostelEase)

> **AIM**: To develop a complete database-driven web application with user authentication and CRUD modules using Laravel and AI coding assistants, to deploy it on a hosting server using SSH, and to manage further updates through GitHub.

---

## 🏛️ Project Overview

**HostelEase** is a modern, responsive, database-driven web application built with **Laravel 11/12**, **Blade templates**, and a **clean white visual design system** (`clean-white.css`). The application manages student hostel room allocations, room vacancies, occupancy analytics, and resident maintenance requests across university hostel blocks.

### ✨ Key Features
- **Pristine Clean White Aesthetic**: Predominantly white surfaces (`#ffffff`) with subtle light borders, high-contrast dark typography, and restrained accent badges for status indicators.
- **Relational Database Design**: 5 interrelated tables designed using Laravel migrations with primary/foreign keys and cascade constraints:
  - `users` (Admin, Warden, Student roles)
  - `rooms` (Block, room number, type, capacity, current occupancy, status, fee, amenities)
  - `students` (Roll number, full name, course, semester, contact, guardian details)
  - `room_allocations` (Foreign keys to `rooms`, `students`, and `users`, bed assignment, fee status)
  - `maintenance_requests` (Foreign keys to `rooms` and `students`, priority, repair status)
- **User Authentication**: Secure session-based login, registration, and logout with password hashing. Only logged-in users may create, edit, or delete records.
- **Complete CRUD Modules**:
  1. **Rooms Module**: Create, View, Edit, Delete (with active occupancy safety check), and Browse.
  2. **Allocations Module**: Bed allocation with auto-capacity validation, Edit, Print Allotment Slip, Vacate student (auto-releases bed).
  3. **Students Module**: Register resident profile, View allotment history, Update particulars.
  4. **Maintenance Module**: Submit repair tickets, update status (`reported`, `in_progress`, `resolved`).
- **Search & Multi-Filter Facility**:
  - Search rooms by keyword (room number, block, amenities).
  - Filter by Block (Boys Wing, Girls Wing, Scholars Wing).
  - Filter by Status (`available`, `full`, `maintenance`).
  - Filter by Room Type (`Single Deluxe`, `Double Sharing`, `Triple Sharing`).
  - Search allocations by student name or roll number.
- **Occupancy & Summary Report View**:
  - Comprehensive analytical dashboard displaying total rooms, bed capacity, occupied beds, vacancy roster, occupancy percentage, and revenue estimation.
  - Block-wise utilization table and printable format.
- **Automated Test Suite**: 8 PHPUnit feature and unit tests passing with 39 assertions.

---

## 🚀 Quick Start Guide (Local Setup)

### Prerequisites
- PHP 8.2 or higher (with `pdo_sqlite`, `mbstring`, `openssl`, `curl` enabled)
- Composer
- SQLite (included with PHP)

### Installation Steps

1. **Clone or Open Project Directory**:
   ```bash
   cd Practical29
   ```

2. **Install PHP Dependencies**:
   ```bash
   composer install
   ```

3. **Configure Environment File**:
   ```bash
   copy .env.example .env
   php artisan key:generate
   ```

4. **Run Migrations & Seed Sample Data**:
   ```bash
   php artisan migrate:fresh --seed
   ```

5. **Start Local Development Server**:
   ```bash
   php artisan serve --port=8000
   ```

6. Open your web browser at: **`http://127.0.0.1:8000`**

---

## 🔑 Default Login Credentials

| Role | Email | Password | Access Rights |
| :--- | :--- | :--- | :--- |
| **System Admin** | `admin@hostel.edu` | `password` | Full CRUD access to all modules, reports, and rooms |
| **Hostel Warden** | `warden@hostel.edu` | `password` | Allocate beds, manage rooms, process vacates |
| **Student Resident** | `student@hostel.edu` | `password` | View personal allocation slip, submit maintenance requests |

---

## 🧪 Running Automated Tests

Run the full PHPUnit feature test suite:
```bash
php artisan test
```

All 8 tests will execute and pass:
- `test_guest_can_view_rooms_catalog_and_filter`
- `test_guest_is_redirected_to_login_for_protected_actions`
- `test_user_can_login_and_access_dashboard`
- `test_authenticated_user_can_create_and_view_room`
- `test_room_allocation_workflow_and_bed_release`
- `test_occupancy_report_lists_records`
- `test_the_application_root_redirects_to_dashboard`
- `test_unit_example`

---

## 📁 Project Structure

```
Practical29/
├── app/
│   ├── Http/Controllers/
│   │   ├── AuthController.php            # Login, registration, session logout
│   │   ├── DashboardController.php       # Overview statistics & recent allocations
│   │   ├── RoomController.php            # Module 1: Rooms CRUD & search/filters
│   │   ├── RoomAllocationController.php  # Module 2: Allocations CRUD & vacate workflow
│   │   ├── StudentController.php         # Module 3: Students directory CRUD
│   │   ├── ReportController.php          # Module 4: Occupancy summary report
│   │   └── MaintenanceController.php     # Module 5: Facility repair tickets
│   └── Models/
│       ├── User.php                      # User account model with role checks
│       ├── Room.php                      # Room model with auto-occupancy syncing
│       ├── Student.php                   # Student profile with allocations relation
│       ├── RoomAllocation.php            # Relational allocation model
│       └── MaintenanceRequest.php        # Maintenance ticket model
├── database/
│   ├── migrations/                       # 5 relational database migration files
│   └── seeders/DatabaseSeeder.php        # Demo dataset with rooms, students, and allocations
├── public/
│   └── css/clean-white.css               # Clean white CSS design system
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php                 # Master clean white layout & flash alerts
│   │   └── navbar.blade.php              # Sticky clean white navigation bar
│   ├── auth/                             # Login & Register views
│   ├── rooms/                            # Index, Create, Edit, Show views
│   ├── allocations/                      # Index, Create, Edit, Show (printable slip)
│   ├── students/                         # Index, Create, Edit, Show profile
│   ├── reports/                          # Occupancy summary analytical report
│   └── maintenance/                      # Issues index & creation form
├── routes/web.php                        # Authenticated and public routes
└── tests/Feature/HostelManagementTest.php # Automated feature tests
```

---

## 📄 Deliverables & Submission Documentation

For the complete **List of AI Prompts Used**, **SSH Hosting Server Deployment Commands**, and **GitHub `git pull` Workflow Demonstration**, see:
👉 **[DEPLOYMENT_AND_AI_PROMPTS.md](DEPLOYMENT_AND_AI_PROMPTS.md)**
