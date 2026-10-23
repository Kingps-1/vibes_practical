<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\RoomAllocationController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\MaintenanceController;

/*
|--------------------------------------------------------------------------
| Web Routes - Hostel Room Allocation System
|--------------------------------------------------------------------------
*/

// Public landing & room availability catalog
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Authentication routes (Guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Public read access to rooms catalog & search
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show')->whereNumber('room');

// Protected Routes (Authentication Required - only logged in users can add/edit/delete)
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Module 1: Rooms Management (Protected CRUD actions)
    Route::get('/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');

    // Module 2: Room Allocations Management (Full CRUD)
    Route::get('/allocations', [RoomAllocationController::class, 'index'])->name('allocations.index');
    Route::get('/allocations/create', [RoomAllocationController::class, 'create'])->name('allocations.create');
    Route::post('/allocations', [RoomAllocationController::class, 'store'])->name('allocations.store');
    Route::get('/allocations/{allocation}', [RoomAllocationController::class, 'show'])->name('allocations.show');
    Route::get('/allocations/{allocation}/edit', [RoomAllocationController::class, 'edit'])->name('allocations.edit');
    Route::put('/allocations/{allocation}', [RoomAllocationController::class, 'update'])->name('allocations.update');
    Route::post('/allocations/{allocation}/vacate', [RoomAllocationController::class, 'vacate'])->name('allocations.vacate');
    Route::delete('/allocations/{allocation}', [RoomAllocationController::class, 'destroy'])->name('allocations.destroy');

    // Module 3: Student Profiles (Full CRUD)
    Route::resource('students', StudentController::class);

    // Reports & Summary View
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

    // Maintenance Requests
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::get('/maintenance/create', [MaintenanceController::class, 'create'])->name('maintenance.create');
    Route::post('/maintenance', [MaintenanceController::class, 'store'])->name('maintenance.store');
    Route::patch('/maintenance/{maintenance}/status', [MaintenanceController::class, 'updateStatus'])->name('maintenance.updateStatus');
});
