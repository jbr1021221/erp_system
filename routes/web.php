<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Students
    Route::resource('students', StudentController::class);
    
    // Payments
    Route::resource('payments', PaymentController::class);
    Route::get('payments/student/{student}', [PaymentController::class, 'studentPayments'])->name('payments.student');
    
    // Classes
    Route::resource('classes', ClassController::class);
    Route::get('classes/{class}/sections', [ClassController::class, 'getSections']);
    
    // Expenses
    Route::resource('expenses', ExpenseController::class);
    Route::post('expenses/{expense}/approve', [ExpenseController::class, 'approve'])->name('expenses.approve');
    
    // Accounts
    Route::resource('accounts', AccountController::class);
    
    // Users
    Route::resource('users', UserController::class);
    
    // Roles & Permissions
    Route::resource('roles', RoleController::class);
    
    // Reports
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/students', [ReportController::class, 'students'])->name('reports.students');
    Route::get('reports/payments', [ReportController::class, 'payments'])->name('reports.payments');
    Route::get('reports/expenses', [ReportController::class, 'expenses'])->name('reports.expenses');
    Route::get('reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';