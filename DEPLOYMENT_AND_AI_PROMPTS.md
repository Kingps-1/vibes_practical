# Program No. 29: Major Project Deliverables Documentation
## AI Prompts, Database Schema, SSH Deployment, and GitHub Workflow

---

## 📋 1. Project Specifications Compliance Matrix

| Specification Item | Requirement | Implementation in HostelEase | Status |
| :--- | :--- | :--- | :--- |
| **Theme Selection** | Library / Attendance / Hostel / Inventory / Complaint | **Hostel Room Allocation System** | ✅ Compliant |
| **Database Migrations** | Minimum 3 related tables with proper PK/FK | **5 Related Tables**: `users`, `rooms`, `students`, `room_allocations`, `maintenance_requests` | ✅ Compliant |
| **Authentication** | User login & logout, only logged-in user can add/edit/delete | Session auth with `auth` middleware, bcrypt hashing, role check (`admin`, `warden`, `student`) | ✅ Compliant |
| **CRUD Modules** | Complete CRUD operations for at least 2 modules with validation | **Module 1**: Rooms Management CRUD<br>**Module 2**: Allocations CRUD (with auto capacity & vacate)<br>**Module 3**: Students Directory CRUD | ✅ Compliant |
| **Search / Filter** | One search or filter facility | Search by keyword (room number, block, amenities) + multi-filters for Block, Type, Status, Floor | ✅ Compliant |
| **Report / Summary** | One report or summary view listing records | Dedicated **Hostel Occupancy & Summary Report** (`/reports`) with block breakdown and available beds roster | ✅ Compliant |
| **User Interface** | Responsive Blade layouts and clean design | Responsive Blade layouts with **predominantly clean white aesthetic** (`clean-white.css`) and subtle accent badges | ✅ Compliant |
| **Automated Tests** | Validated functionality | 8 PHPUnit feature & unit tests passing with 39 assertions | ✅ Compliant |
| **Deployment & Git** | SSH deployment commands and GitHub sync | Complete SSH command guide and remote `git pull` demonstration commands provided below | ✅ Compliant |

---

## 🤖 2. List of AI Prompts Used

The following AI prompts were used sequentially to architect, develop, refine, and test the project:

### Prompt 1: Project Architecture & Theme Selection
> *"Develop a database-driven web application using Laravel for Program No. 29 on the theme 'Hostel Room Allocation'. The database must have at least 3 related tables with primary and foreign key constraints. The UI must use a clean, neat, predominantly white design with minimal accent colors. Only logged-in users may create, edit, or delete records. Include full CRUD for rooms and allocations, search/filters, and an occupancy summary report."*

### Prompt 2: Database Schema & Migrations
> *"Write Laravel migration files for 5 related tables: 1) users table with role and phone, 2) rooms table with room_number, block, room_type, capacity, current_occupancy, monthly_fee, floor, amenities, and status, 3) students table with student_id_number, user_id (FK), full_name, course, semester, contact, and guardian details, 4) room_allocations table with room_id (FK), student_id (FK), allocated_by (FK), bed_number, allocation_date, vacate_date, fee_status, and status, 5) maintenance_requests table with room_id (FK) and student_id (FK). Ensure all foreign key constraints and cascade rules are properly defined."*

### Prompt 3: Eloquent Models & Business Logic
> *"Generate Laravel Eloquent models for User, Room, Student, RoomAllocation, and MaintenanceRequest with all hasMany, belongsTo, and hasOne relationships. Add a helper method syncOccupancy() on the Room model to automatically recalculate current_occupancy and toggle status between 'available' and 'full' based on active allocations. Prevent deleting a room if active allocations exist."*

### Prompt 4: Clean White Design System (CSS)
> *"Design a custom CSS stylesheet `clean-white.css` adhering to a pristine white, minimalist, modern aesthetic. The page background should be ultra-clean off-white `#f8fafc` and cards, tables, inputs, and navbar should be pure white `#ffffff` with subtle borders (`#e2e8f0`) and soft elevation. Restrict vibrant colors to small status badges (emerald for available/paid, rose for full/urgent, amber for pending). Use Plus Jakarta Sans typography."*

### Prompt 5: Controllers & Validation
> *"Create AuthController, DashboardController, RoomController, RoomAllocationController, StudentController, ReportController, and MaintenanceController. Implement request validation with meaningful error messages, flash notifications (success/error), pagination, search query scoping, and role-based redirects."*

### Prompt 6: Responsive Blade Views & Printable Slip
> *"Build responsive Blade templates for the application: master layout (`layouts/app.blade.php`), navigation bar, login/register forms with demo quick-fill buttons, rooms catalog with filter bar, room creation and editing forms, room detail page with active occupants list, allocations table with vacate action, a printable official Allotment Slip receipt with institution header and signature lines, and an Occupancy Summary Report view with block-wise breakdown tables."*

### Prompt 7: Database Seeding & Automated Tests
> *"Create a DatabaseSeeder with realistic sample records: 3 user accounts (admin, warden, student), 8 rooms across Block A, Block B, and Block C, 5 students, 4 room allocations, and 2 maintenance requests. Write automated PHPUnit feature tests to verify guest redirects, authentication, room creation, bed allocation occupancy sync, and report generation."*

---

## 🗄️ 3. Database Schema Diagram (Relational Structure)

```
+------------------+         +-------------------------+         +----------------------+
|      USERS       |         |         ROOMS           |         |       STUDENTS       |
+------------------+         +-------------------------+         +----------------------+
| id (PK)          |         | id (PK)                 |         | id (PK)              |
| name             |         | room_number (UNIQUE)    |         | user_id (FK -> users)|
| email (UNIQUE)   |         | block                   |         | student_id_number    |
| password         |         | room_type               |         | full_name            |
| role             |         | capacity                |         | gender               |
| phone            |         | current_occupancy       |         | course               |
| timestamps       |         | monthly_fee             |         | semester             |
+--------+---------+         | floor                   |         | email (UNIQUE)       |
         |                   | status                  |         | phone                |
         |                   | amenities               |         | guardian_name        |
         |                   | timestamps              |         | guardian_phone       |
         |                   +------------+------------+         +----------+-----------+
         |                                |                                 |
         |        +-----------------------+---------------------------------+
         |        |
         v        v
+------------------------------------+         +-------------------------------------+
|          ROOM_ALLOCATIONS          |         |        MAINTENANCE_REQUESTS         |
+------------------------------------+         +-------------------------------------+
| id (PK)                            |         | id (PK)                             |
| room_id (FK -> rooms.id)           |         | room_id (FK -> rooms.id)            |
| student_id (FK -> students.id)     |         | student_id (FK -> students.id)      |
| allocated_by (FK -> users.id)      |         | title                               |
| bed_number                         |         | description                         |
| allocation_date                    |         | priority (urgent/high/med/low)      |
| vacate_date                        |         | status (reported/in_progress/done)  |
| status (active/vacated/cancelled)  |         | reported_at                         |
| fee_status (paid/pending/partial)  |         | resolved_at                         |
| remarks                            |         | timestamps                          |
| timestamps                         |         +-------------------------------------+
+------------------------------------+
```

---

## 🌐 4. Hosting Server Deployment via SSH

Follow these exact steps to deploy the application to any cloud VPS (Ubuntu 22.04 / 24.04 on DigitalOcean, AWS EC2, Linode, or Hostinger VPS):

### Step 1: Connect to the Server via SSH
Open your local terminal (PowerShell or Bash) and execute:
```bash
ssh username@YOUR_SERVER_IP
# Example: ssh ubuntu@13.233.100.50
```

### Step 2: Install Server Dependencies (PHP 8.2, Composer, Nginx, SQLite)
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y nginx git curl unzip sqlite3 php8.2 php8.2-fpm php8.2-cli \
    php8.2-sqlite3 php8.2-mbstring php8.2-xml php8.2-curl php8.2-bcmath php8.2-zip

# Install Composer globally
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer
```

### Step 3: Clone the GitHub Repository on the Server
```bash
cd /var/www
sudo git clone https://github.com/YOUR_GITHUB_USERNAME/Practical29.git hostel-app
cd /var/www/hostel-app
```

### Step 4: Configure Environment & Install Packages
```bash
# Copy environment configuration
sudo cp .env.example .env

# Install production dependencies
sudo composer install --no-dev --optimize-autoloader

# Generate secure application key
sudo php artisan key:generate

# Prepare SQLite database and run migrations with seed data
sudo touch database/database.sqlite
sudo php artisan migrate:fresh --seed --force

# Optimize Laravel routes, views, and configurations for production
sudo php artisan config:cache
sudo php artisan route:cache
sudo php artisan view:cache
```

### Step 5: Configure Proper File Permissions
```bash
sudo chown -R www-data:www-data /var/www/hostel-app
sudo chmod -R 775 /var/www/hostel-app/storage /var/www/hostel-app/bootstrap/cache /var/www/hostel-app/database
```

### Step 6: Configure Nginx Virtual Host
Create an Nginx configuration file:
```bash
sudo nano /etc/nginx/sites-available/hostel
```
Paste the following server block:
```nginx
server {
    listen 80;
    server_name your-domain.com YOUR_SERVER_IP;
    root /var/www/hostel-app/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php index.html;
    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable the site and restart Nginx:
```bash
sudo ln -s /etc/nginx/sites-available/hostel /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

---

## 🔄 5. Managing Updates Through GitHub (Demonstration Workflow)

The project specification requires demonstrating at least one change pushed to GitHub from the local environment and pulled on the remote server:

### A. Local Machine: Making and Pushing an Update
```bash
# 1. Initialize local git repository (if not already initialized)
git init
git add .
git commit -m "feat: complete hostel room allocation system with clean white UI"

# 2. Add your GitHub repository remote
git remote add origin https://github.com/YOUR_USERNAME/Practical29.git
git branch -M main
git push -u origin main

# 3. Make a demonstrative update (e.g. updating version or header in resources/views/layouts/app.blade.php)
git add .
git commit -m "update: refined room search filters and report summary layout"
git push origin main
```

### B. Remote Hosting Server: Applying the Update via `git pull`
Connect to your remote hosting server via SSH and execute:
```bash
cd /var/www/hostel-app

# Pull latest changes from GitHub
sudo git pull origin main

# Re-optimize Laravel cache
sudo php artisan view:clear
sudo php artisan view:cache
sudo php artisan config:cache
sudo php artisan route:cache

# If migrations were modified:
# sudo php artisan migrate --force
```

---

## 📸 6. Module Screenshots & Deliverables Verification Checklist

Take screenshots of the following working screens for your submission document:
1. **Screen 1 - Sign In Page (`/login`)**: Displaying clean white card, username/password fields, and quick demo credentials.
2. **Screen 2 - Dashboard Overview (`/dashboard`)**: Showing KPI cards (Total Rooms: 8, Occupancy Rate: 28.6%, Available Beds: 10), quick shortcuts, and recent allocations.
3. **Screen 3 - Rooms Catalog & Multi-Filter (`/rooms`)**: Showing room search bar, Block filters, occupancy progress indicators, and status badges.
4. **Screen 4 - Room Detail Page (`/rooms/1`)**: Showing room specifications, current occupants list, and maintenance tickets.
5. **Screen 5 - New Allocation Form (`/allocations/create`)**: Demonstrating student dropdown, available room dropdown, bed selection, and validation.
6. **Screen 6 - Official Printable Allotment Slip (`/allocations/1`)**: Showing institutional letterhead, student particulars, assigned bed, fee status, and signature lines.
7. **Screen 7 - Occupancy & Summary Report View (`/reports`)**: Showing block-by-block occupancy table, revenue estimates, and active resident master listing.
8. **Screen 8 - Terminal Test Run (`php artisan test`)**: Demonstrating all 8 automated tests passing (39 assertions).
