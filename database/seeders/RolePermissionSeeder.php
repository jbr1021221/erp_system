<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Permissions
        $permissions = [
            // Student permissions
            'student-list',
            'student-create',
            'student-edit',
            'student-delete',
            'student-view',

            // Teacher permissions
            'teacher-list',
            'teacher-create',
            'teacher-edit',
            'teacher-delete',
            'teacher-view',
            
            // Payment permissions
            'payment-list',
            'payment-create',
            'payment-edit',
            'payment-delete',
            'payment-view',
            
            // Class permissions
            'class-list',
            'class-create',
            'class-edit',
            'class-delete',
            
            // User permissions
            'user-list',
            'user-create',
            'user-edit',
            'user-delete',
            
            // Expense permissions
            'expense-list',
            'expense-create',
            'expense-edit',
            'expense-delete',
            'expense-approve',
            'expense-view',
            
            // Account permissions
            'account-list',
            'account-create',
            'account-edit',
            'account-view',
            
            // Report permissions
            'report-view',
            'report-export',
            
            // Role & Permission management
            'role-list',
            'role-create',
            'role-edit',
            'role-delete',
            'permission-assign',
            
            // Dashboard access
            'dashboard-admin',
            'dashboard-accountant',
            'dashboard-teacher',
            'dashboard-student',
            'dashboard-parent',

            // System Settings
            'setting-list',
            'setting-edit',

            // Fee Structure
            'fee-list',
            'fee-structure-create',
            'fee-structure-edit',
            'fee-structure-delete',

            // Reports
            'report-view',
            'report-income',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create Roles and assign permissions

        // Super Admin - All permissions
        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->syncPermissions(Permission::all());

        // Admin
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions([
            'student-list', 'student-create', 'student-edit', 'student-view',
            'teacher-list', 'teacher-create', 'teacher-edit', 'teacher-view',
            'payment-list', 'payment-create', 'payment-view',
            'class-list', 'class-create', 'class-edit',
            'user-list', 'user-create', 'user-edit',
            'expense-list', 'expense-view',
            'report-view', 'report-export',
            'setting-list', 'setting-edit',
            'fee-list', 'fee-structure-create', 'fee-structure-edit', 'fee-structure-delete',
            'dashboard-admin',
        ]);

        // Accountant
        $accountant = Role::firstOrCreate(['name' => 'Accountant']);
        $accountant->syncPermissions([
            'student-list', 'student-view',
            'payment-list', 'payment-create', 'payment-edit', 'payment-view',
            'expense-list', 'expense-create', 'expense-edit', 'expense-approve',
            'account-list', 'account-create', 'account-edit', 'account-view',
            'report-view', 'report-export',
            'dashboard-accountant',
        ]);

        // Teacher
        $teacher = Role::firstOrCreate(['name' => 'Teacher']);
        $teacher->syncPermissions([
            'student-list', 'student-view',
            'class-list',
            'dashboard-teacher',
        ]);

        // Student
        $student = Role::firstOrCreate(['name' => 'Student']);
        $student->syncPermissions([
            'payment-list', 'payment-view',
            'dashboard-student',
        ]);

        // Parent
        $parent = Role::firstOrCreate(['name' => 'Parent']);
        $parent->syncPermissions([
            'student-view',
            'payment-list', 'payment-view', 'payment-create',
            'dashboard-parent',
        ]);

        // Create Super Admin User
        $user = User::firstOrCreate(
            ['email' => 'admin@erp.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('password'),
                'status' => 'active',
            ]
        );
        $user->assignRole('Super Admin');
    }
}