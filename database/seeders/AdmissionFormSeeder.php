<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;

class AdmissionFormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // Header Settings
            [
                'key' => 'admission_form_logo',
                'value' => '/images/logo.png',
                'display_name' => 'Admission Form Logo',
                'group' => 'admission_form',
            ],
            [
                'key' => 'admission_form_banner',
                'value' => '/images/banner.jpg',
                'display_name' => 'Admission Form Banner',
                'group' => 'admission_form',
            ],
            [
                'key' => 'admission_form_institution_name',
                'value' => 'ERP Institution',
                'display_name' => 'Institution Name',
                'group' => 'admission_form',
            ],
            [
                'key' => 'admission_form_institution_address',
                'value' => 'Address Line 1, City, Country',
                'display_name' => 'Institution Address',
                'group' => 'admission_form',
            ],
            [
                'key' => 'admission_form_phone',
                'value' => '+880 1234-567890',
                'display_name' => 'Phone Number',
                'group' => 'admission_form',
            ],
            [
                'key' => 'admission_form_email',
                'value' => 'info@institution.edu',
                'display_name' => 'Email Address',
                'group' => 'admission_form',
            ],
            [
                'key' => 'admission_form_website',
                'value' => 'www.institution.edu',
                'display_name' => 'Website',
                'group' => 'admission_form',
            ],
            [
                'key' => 'admission_form_title',
                'value' => 'STUDENT ADMISSION FORM',
                'display_name' => 'Form Title',
                'group' => 'admission_form',
            ],
            [
                'key' => 'admission_form_academic_year',
                'value' => '2024-2025',
                'display_name' => 'Academic Year',
                'group' => 'admission_form',
            ],
        ];

        foreach ($settings as $setting) {
            GeneralSetting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
