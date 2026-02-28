<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\FeeStructureController;
use App\Http\Controllers\Admin\ClassController;
use App\Http\Controllers\Admin\ExpenseController;
use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', function(\Illuminate\Http\Request $request) {
        $months = (int) $request->get('months', 6);
        $data = [];
        $labels = [];
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            // Replace with your actual payment model query:
            $data[] = \App\Models\Payment::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->sum('amount') ?? 0;
        }
        return response()->json(['labels' => $labels, 'data' => $data]);
    });
    
    // Students
    Route::resource('students', StudentController::class);
    Route::get('students/{student}/admission-form', [StudentController::class, 'admissionForm'])->name('students.admission-form');
    
    // Multi-step Admission Process
    Route::get('students/{student}/select-fees', [StudentController::class, 'selectFees'])->name('students.select-fees');
    Route::post('students/{student}/store-fees', [StudentController::class, 'storeFees'])->name('students.store-fees');
    Route::get('students/{student}/admission-payment', [StudentController::class, 'admissionPayment'])->name('students.admission-payment');
    Route::post('students/{student}/store-admission-payment', [StudentController::class, 'storeAdmissionPayment'])->name('students.store-admission-payment');
    Route::get('students/{student}/admission-preview', [StudentController::class, 'admissionPreview'])->name('students.admission-preview');
    Route::get('students/{student}/admission-complete', [StudentController::class, 'admissionComplete'])->name('students.admission-complete');
    
    // Teachers
    Route::resource('teachers', TeacherController::class);
    
    // Payments
    Route::resource('payments', PaymentController::class);
    Route::get('payments/receipt/{receipt_number}', [PaymentController::class, 'receipt'])->name('payments.receipt');
    Route::resource('fee-structures', FeeStructureController::class);
    Route::get('payments/student/{student}', [PaymentController::class, 'studentPayments'])->name('payments.student');
    
    // Classes
    Route::resource('classes', ClassController::class);
    Route::get('classes/{class}/sections', [ClassController::class, 'getSections']);
    Route::post('classes/{class}/sections', [ClassController::class, 'storeSection'])->name('classes.sections.store');
    Route::delete('classes/{class}/sections/{section}', [ClassController::class, 'destroySection'])->name('classes.sections.destroy');
    
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
    Route::get('reports/fees', [ReportController::class, 'fees'])->name('reports.fees');

    // Settings
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';