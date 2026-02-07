<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'student_id_prefix', 'value' => 'STD-', 'display_name' => 'Student ID Prefix', 'group' => 'identifier'],
            ['key' => 'class_id_prefix', 'value' => 'CLS-', 'display_name' => 'Class ID Prefix', 'group' => 'identifier'],
            ['key' => 'receipt_id_prefix', 'value' => 'REC-', 'display_name' => 'Receipt ID Prefix', 'group' => 'receipt'],
            ['key' => 'receipt_name', 'value' => 'Official Payment Receipt', 'display_name' => 'Receipt Title', 'group' => 'receipt'],
            ['key' => 'role_permission_style', 'value' => 'badge', 'display_name' => 'Role/Permission Style', 'group' => 'style'],
        ];

        foreach ($settings as $setting) {
            \App\Models\GeneralSetting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
