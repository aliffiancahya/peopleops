<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\PayrollsController;
use App\Http\Controllers\PresencesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::middleware(['auth', 'employee'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/dashboard/presence', [DashboardController::class, 'presence'])
        ->name('dashboard.presence');


    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');


    // Departments, roles and employees (HR only)
    Route::resource('departments', DepartmentController::class)
        ->except(['show'])
        ->middleware('role:HR');

    Route::resource('roles', RoleController::class)
        ->except(['show'])
        ->middleware('role:HR');

    Route::resource('employees', EmployeeController::class)
        ->middleware('role:HR');


    // Tasks

    // Available to all authenticated employees
    Route::resource('tasks', TaskController::class)
        ->only(['index']);

    // HR only: create, store, edit, update and delete
    Route::resource('tasks', TaskController::class)
        ->except(['index', 'show'])
        ->middleware('role:HR');

    // Register show AFTER create
    Route::resource('tasks', TaskController::class)
        ->only(['show']);

    Route::patch('tasks/{task}/status', [TaskController::class, 'status'])
        ->name('tasks.status');


    // Payroll

    // Available to all authenticated employees
    Route::resource('payrolls', PayrollsController::class)
        ->only(['index']);

    // HR only: create, store, edit, update and delete
    Route::resource('payrolls', PayrollsController::class)
        ->except(['index', 'show'])
        ->middleware('role:HR');

    // Register show AFTER create
    Route::resource('payrolls', PayrollsController::class)
        ->only(['show']);


    // Attendance

    Route::resource('presences', PresencesController::class)
        ->only(['index', 'create', 'store']);

    Route::resource('presences', PresencesController::class)
        ->only(['edit', 'update', 'destroy'])
        ->middleware('role:HR');

    Route::patch(
        'presences/{presence}/checkout',
        [PresencesController::class, 'checkout']
    )->name('presences.checkout');


    // Leave requests

    Route::resource('leave-requests', LeaveRequestController::class)
        ->only(['index', 'create', 'store']);

    Route::resource('leave-requests', LeaveRequestController::class)
        ->only(['edit', 'update', 'destroy'])
        ->middleware('role:HR');

    Route::patch(
        'leave-requests/{leaveRequest}/confirm',
        [LeaveRequestController::class, 'confirm']
    )
        ->middleware('role:HR')
        ->name('leave-requests.confirm');

    Route::patch(
        'leave-requests/{leaveRequest}/reject',
        [LeaveRequestController::class, 'reject']
    )
        ->middleware('role:HR')
        ->name('leave-requests.reject');
});

require __DIR__ . '/auth.php';
