<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GeneralSetting;

class GeneralSettingSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            // General Settings
            ['key' => 'institution_name', 'value' => 'EduERP Institution', 'display_name' => 'Institution Name', 'group' => 'general'],
            ['key' => 'institution_email', 'value' => 'admin@eduerp.com', 'display_name' => 'Institution Email', 'group' => 'general'],
            ['key' => 'institution_phone', 'value' => '+1234567890', 'display_name' => 'Institution Phone', 'group' => 'general'],
            ['key' => 'institution_address', 'value' => '123 Education St, Knowledge City', 'display_name' => 'Institution Address', 'group' => 'general'],
            
            // Academic Settings
            ['key' => 'academic_year', 'value' => '2025-2026', 'display_name' => 'Current Academic Year', 'group' => 'academic'],
            ['key' => 'admission_prefix', 'value' => 'STU', 'display_name' => 'Admission Number Prefix', 'group' => 'academic'],
            
            // Finance Settings
            ['key' => 'currency', 'value' => 'BDT', 'display_name' => 'Currency', 'group' => 'finance'],
            ['key' => 'currency_symbol', 'value' => '৳', 'display_name' => 'Currency Symbol', 'group' => 'finance'],
            ['key' => 'tax_percentage', 'value' => '0', 'display_name' => 'Tax Percentage', 'group' => 'finance'],
            
            // UI Settings
            ['key' => 'font_size', 'value' => 'text-sm', 'display_name' => 'Default Font Size', 'group' => 'ui'],
            ['key' => 'theme_primary_color', 'value' => '#fff5e6', 'display_name' => 'Theme Primary Color', 'group' => 'ui'],
            ['key' => 'theme_secondary_color', 'value' => '#fff0d9', 'display_name' => 'Theme Secondary Color', 'group' => 'ui'],
            ['key' => 'role_permission_style', 'value' => 'badge', 'display_name' => 'Role/Permission Display Style', 'group' => 'ui'],
            
            // Admission Form
            ['key' => 'admission_form_logo', 'value' => '/images/logo.png', 'display_name' => 'Admission Form Logo', 'group' => 'admission_form'],
            ['key' => 'admission_form_banner', 'value' => '/images/banner.jpg', 'display_name' => 'Admission Form Banner', 'group' => 'admission_form'],
        ];

        foreach ($settings as $setting) {
            GeneralSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
