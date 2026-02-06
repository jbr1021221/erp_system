<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\User;
use App\Models\Classes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{


    public function index()
    {
        $user = auth()->user();
        
        // Get user's primary role
        $role = $user->getRoleNames()->first();

        // Route to appropriate dashboard based on role
        switch ($role) {
            case 'Super Admin':
            case 'Admin':
                return $this->adminDashboard();
            
            case 'Accountant':
                return $this->accountantDashboard();
            
            case 'Teacher':
                return $this->teacherDashboard();
            
            case 'Student':
                return $this->studentDashboard();
            
            case 'Parent':
                return $this->parentDashboard();
            
            default:
                return $this->defaultDashboard();
        }
    }

    /**
     * Super Admin / Admin Dashboard
     */
    private function adminDashboard()
    {
        // Get statistics
        $stats = [
            'total_students' => Student::count(),
            'active_students' => Student::where('status', 'active')->count(),
            'inactive_students' => Student::where('status', 'inactive')->count(),
            'total_classes' => Classes::where('is_active', true)->count(),
            'total_users' => User::where('status', 'active')->count(),
            'total_teachers' => User::role('Teacher')->count(),
            
            // Financial stats
            'total_revenue' => Payment::where('status', 'completed')->sum('amount'),
            'today_revenue' => Payment::where('status', 'completed')
                ->whereDate('payment_date', today())
                ->sum('amount'),
            'this_month_revenue' => Payment::where('status', 'completed')
                ->whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('amount'),
            'total_expenses' => Expense::where('status', 'paid')->sum('amount'),
            'pending_expenses' => Expense::where('status', 'pending')->count(),
            
            // Outstanding fees
            'total_outstanding' => $this->calculateOutstandingFees(),
        ];

        // Recent admissions (last 7 days)
        $recentAdmissions = Student::with(['class', 'section'])
            ->where('created_at', '>=', now()->subDays(7))
            ->latest()
            ->limit(5)
            ->get();

        // Recent payments
        $recentPayments = Payment::with(['student'])
            ->latest('payment_date')
            ->limit(10)
            ->get();

        // Monthly revenue chart data (last 6 months)
        $monthlyRevenue = Payment::where('status', 'completed')
            ->where('payment_date', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(payment_date, "%Y-%m") as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Student status distribution
        $studentStatusDistribution = Student::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Class-wise student count
        $classWiseStudents = Classes::withCount(['students' => function($query) {
                $query->where('status', 'active');
            }])
            ->where('is_active', true)
            ->get();

        return view('dashboard.admin', compact(
            'stats',
            'recentAdmissions',
            'recentPayments',
            'monthlyRevenue',
            'studentStatusDistribution',
            'classWiseStudents'
        ));
    }

    /**
     * Accountant Dashboard
     */
    private function accountantDashboard()
    {
        $stats = [
            'total_students' => Student::where('status', 'active')->count(),
            
            // Today's collection
            'today_collection' => Payment::where('status', 'completed')
                ->whereDate('payment_date', today())
                ->sum('amount'),
            'today_transactions' => Payment::whereDate('payment_date', today())->count(),
            
            // This month
            'month_collection' => Payment::where('status', 'completed')
                ->whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('amount'),
            
            // Outstanding
            'total_outstanding' => $this->calculateOutstandingFees(),
            
            // Expenses
            'month_expenses' => Expense::where('status', 'paid')
                ->whereMonth('expense_date', now()->month)
                ->whereYear('expense_date', now()->year)
                ->sum('amount'),
            'pending_expenses' => Expense::where('status', 'pending')->count(),
            'pending_approval_expenses' => Expense::where('status', 'pending')->sum('amount'),
        ];

        // Recent payments
        $recentPayments = Payment::with(['student', 'receivedBy'])
            ->latest('payment_date')
            ->limit(15)
            ->get();

        // Payment method distribution (this month)
        $paymentMethodDistribution = Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->select('payment_method', DB::raw('SUM(amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Top defaulters (students with highest outstanding)
        $topDefaulters = $this->getTopDefaulters(10);

        // Pending expense approvals
        $pendingExpenses = Expense::with(['category', 'createdBy'])
            ->where('status', 'pending')
            ->latest()
            ->limit(10)
            ->get();

        // Daily collection trend (last 30 days)
        $dailyCollection = Payment::where('status', 'completed')
            ->where('payment_date', '>=', now()->subDays(30))
            ->select(
                DB::raw('DATE(payment_date) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return view('dashboard.accountant', compact(
            'stats',
            'recentPayments',
            'paymentMethodDistribution',
            'topDefaulters',
            'pendingExpenses',
            'dailyCollection'
        ));
    }

    /**
     * Teacher Dashboard
     */
    private function teacherDashboard()
    {
        $user = auth()->user();

        // Classes where user is class teacher
        $myClasses = Classes::where('class_teacher_id', $user->id)
            ->with(['students' => function($query) {
                $query->where('status', 'active');
            }])
            ->get();

        $stats = [
            'my_classes' => $myClasses->count(),
            'total_students' => $myClasses->sum(function($class) {
                return $class->students->count();
            }),
        ];

        // Today's schedule (you can expand this with a timetable system)
        $todaySchedule = []; // Implement based on your timetable structure

        // Recent students in my classes
        $recentStudents = Student::whereIn('class_id', $myClasses->pluck('id'))
            ->where('status', 'active')
            ->latest()
            ->limit(10)
            ->get();

        return view('dashboard.teacher', compact(
            'stats',
            'myClasses',
            'todaySchedule',
            'recentStudents'
        ));
    }

    /**
     * Student Dashboard
     */
    private function studentDashboard()
    {
        // Note: You'll need to link User to Student model
        // For now, assuming user_id exists in students table or create a relationship
        
        // This is a placeholder - adjust based on your user-student relationship
        $student = Student::where('email', auth()->user()->email)->first();

        if (!$student) {
            return view('dashboard.student-no-profile');
        }

        $stats = [
            'class' => $student->class->name ?? 'Not Assigned',
            'section' => $student->section->name ?? '',
            'enrollment_date' => $student->enrollment_date->format('M d, Y'),
        ];

        // Payment history
        $payments = Payment::where('student_id', $student->id)
            ->latest('payment_date')
            ->limit(10)
            ->get();

        // Outstanding fees
        $outstandingFees = $this->calculateStudentOutstanding($student->id);

        return view('dashboard.student', compact(
            'student',
            'stats',
            'payments',
            'outstandingFees'
        ));
    }

    /**
     * Parent Dashboard
     */
    private function parentDashboard()
    {
        // Note: You'll need to implement parent-student relationship
        // For now, this is a placeholder
        
        // Get children (assuming guardian_email matches user email)
        $children = Student::where('guardian_email', auth()->user()->email)
            ->with(['class', 'section'])
            ->get();

        $stats = [
            'total_children' => $children->count(),
        ];

        // Combined payment history for all children
        $payments = Payment::whereIn('student_id', $children->pluck('id'))
            ->latest('payment_date')
            ->limit(15)
            ->get();

        // Total outstanding for all children
        $totalOutstanding = $children->sum(function($child) {
            return $this->calculateStudentOutstanding($child->id);
        });

        return view('dashboard.parent', compact(
            'children',
            'stats',
            'payments',
            'totalOutstanding'
        ));
    }

    /**
     * Default Dashboard (No specific role)
     */
    private function defaultDashboard()
    {
        $stats = [
            'user_name' => auth()->user()->name,
            'user_email' => auth()->user()->email,
            'role' => auth()->user()->getRoleNames()->first() ?? 'No Role Assigned',
        ];

        return view('dashboard.default', compact('stats'));
    }

    /**
     * Helper: Calculate total outstanding fees
     */
    private function calculateOutstandingFees()
    {
        // This is a simplified calculation
        // You'll need to implement proper fee structure logic
        
        // Total fees that should have been collected (example)
        // In real implementation, calculate based on fee structures for each student
        
        $totalExpected = Student::where('status', 'active')->count() * 10000; // Example: 10000 per student
        $totalCollected = Payment::where('status', 'completed')->sum('amount');
        
        return max(0, $totalExpected - $totalCollected);
    }

    /**
     * Helper: Calculate outstanding for a specific student
     */
    private function calculateStudentOutstanding($studentId)
    {
        // Example calculation - implement based on your fee structure
        $student = Student::find($studentId);
        
        if (!$student) return 0;

        // Get fee structure for student's class
        // $feeStructure = FeeStructure::where('class_id', $student->class_id)->sum('amount');
        
        // For now, using a fixed amount as example
        $expectedFees = 50000; // Example annual fees
        
        $paidFees = Payment::where('student_id', $studentId)
            ->where('status', 'completed')
            ->sum('amount');

        return max(0, $expectedFees - $paidFees);
    }

    /**
     * Helper: Get top fee defaulters
     */
    private function getTopDefaulters($limit = 10)
    {
        $students = Student::where('status', 'active')
            ->with(['class'])
            ->get();

        $defaulters = $students->map(function($student) {
            $outstanding = $this->calculateStudentOutstanding($student->id);
            return [
                'student' => $student,
                'outstanding' => $outstanding,
            ];
        })->filter(function($item) {
            return $item['outstanding'] > 0;
        })->sortByDesc('outstanding')
        ->take($limit);

        return $defaulters;
    }
}