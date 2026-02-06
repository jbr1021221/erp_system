<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Classes;
use App\Models\Section;
use App\Models\Student;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\Vendor;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Users for different roles
        $accountant = User::firstOrCreate(
            ['email' => 'accountant@erp.com'],
            [
                'name' => 'John Accountant',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );
        $accountant->assignRole('Accountant');

        $teacher = User::firstOrCreate(
            ['email' => 'teacher@erp.com'],
            [
                'name' => 'Sarah Teacher',
                'password' => Hash::make('password'),
                'status' => 'active',
            ]
        );
        $teacher->assignRole('Teacher');

        // 2. Create Classes and Sections
        $classes = ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5', 'Class 6', 'Class 7', 'Class 8', 'Class 9', 'Class 10'];
        $sections = ['A', 'B'];

        foreach ($classes as $className) {
            $class = Classes::firstOrCreate(
                ['name' => $className],
                [
                    'code' => strtoupper(str_replace(' ', '-', $className)),
                    'academic_year' => date('Y'),
                    'class_teacher_id' => $teacher->id,
                    'is_active' => true,
                    'capacity' => 40
                ]
            );

            foreach ($sections as $sectionName) {
                Section::firstOrCreate(
                    [
                        'name' => $sectionName,
                        'class_id' => $class->id
                    ],
                    [
                        'capacity' => 40
                    ]
                );
            }
        }

        // 3. Create Students
        $allClasses = Classes::with('sections')->get();
        
        // Ensure we have at least one student
        if (Student::count() < 50) {
            for ($i = 1; $i <= 50; $i++) {
                $class = $allClasses->random();
                $section = $class->sections->random();
                
                Student::create([
                    'first_name' => 'Student',
                    'last_name' => 'Name ' . $i,
                    'student_id' => 'ST-' . date('Y') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                    'date_of_birth' => Carbon::now()->subYears(rand(6, 16)),
                    'gender' => rand(0, 1) ? 'male' : 'female',
                    'email' => "student{$i}@school.com",
                    'phone' => '01' . rand(100000000, 999999999),
                    'address' => '123 Fake Street, City',
                    'guardian_name' => 'Parent of Student ' . $i,
                    'guardian_phone' => '01' . rand(100000000, 999999999),
                    'guardian_email' => "parent{$i}@school.com",
                    'class_id' => $class->id,
                    'section_id' => $section->id,
                    'enrollment_date' => Carbon::now()->subMonths(rand(1, 24)),
                    'status' => 'active',
                ]);
            }
        }

        // 4. Create Expense Categories
        $categories = [
            'Utilities' => ['Electricity', 'Water', 'Internet'],
            'Maintenance' => ['Repairs', 'Cleaning'],
            'Salaries' => ['Teaching Staff', 'Non-Teaching Staff'],
            'Supplies' => ['Stationery', 'Teaching Aids'],
            'Events' => ['Sports Day', 'Annual Function']
        ];

        foreach ($categories as $parent => $children) {
            $parentCat = ExpenseCategory::firstOrCreate(
                ['name' => $parent],
                ['code' => strtoupper(substr($parent, 0, 3))]
            );
            
            foreach ($children as $child) {
                ExpenseCategory::firstOrCreate(
                    ['name' => $child],
                    [
                        'parent_id' => $parentCat->id,
                        'code' => strtoupper(substr($parent, 0, 3) . '-' . substr($child, 0, 3))
                    ]
                );
            }
        }

        // 5. Create Vendors
        $vendors = ['Office Depot', 'Power Company', 'Net Provider', 'Cleaning Services Inc'];
        foreach ($vendors as $v) {
            Vendor::firstOrCreate(
                ['name' => $v],
                [
                    'contact_person' => 'Manager',
                    'phone' => '0123456789',
                    'email' => strtolower(str_replace(' ', '', $v)) . '@vendor.com',
                    'address' => 'Vendor Address'
                ]
            );
        }

        // 6. Create Expenses (Randomly)
        if (Expense::count() < 20) {
            $cats = ExpenseCategory::whereNotNull('parent_id')->get();
            $vendorList = Vendor::all();
            
            for ($i = 0; $i < 20; $i++) {
                Expense::create([
                    'category_id' => $cats->random()->id,
                    'amount' => rand(500, 5000),
                    'expense_date' => Carbon::now()->subDays(rand(1, 60)),
                    'voucher_number' => 'EXP-' . rand(10000, 99999), // Added
                    'payment_method' => ['cash', 'bank_transfer', 'cheque', 'card'][rand(0, 3)], // Added
                    'description' => 'Demo expense description',
                    'vendor_id' => $vendorList->random()->id,
                    'status' => rand(0, 1) ? 'paid' : 'pending',
                    'created_by' => $accountant->id,
                    'approved_by' => rand(0, 1) ? 1 : null,
                ]);
            }
        }

        // 7. Create Payments (Randomly)
        if (Payment::count() < 50) {
            $students = Student::all();
            
            foreach ($students as $student) {
                // Add 1-3 payments per student
                for ($k = 0; $k < rand(1, 3); $k++) {
                    Payment::create([
                        'student_id' => $student->id,
                        'amount' => rand(1000, 5000),
                        'payment_date' => Carbon::now()->subDays(rand(1, 90)),
                        'payment_method' => ['cash', 'bank_transfer', 'cheque', 'online'][rand(0, 3)],
                        'transaction_reference' => 'TXN' . rand(100000, 999999),
                        'fee_type' => ['Tuition Fee', 'exam_fee', 'admission_fee'][rand(0, 2)],
                        'status' => 'completed',
                        'remarks' => 'Tuition Fee Payment',
                        'received_by' => $accountant->id,
                        'receipt_number' => 'REC-' . rand(10000, 99999)
                    ]);
                }
            }
        }
    }
}
