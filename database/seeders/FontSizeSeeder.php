<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;

class FontSizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create font size setting if it doesn't exist
        GeneralSetting::firstOrCreate(
            ['key' => 'font_size'],
            [
                'value' => 'medium',
                'display_name' => 'System Font Size',
                'group' => 'appearance',
            ]
        );
    }
}
