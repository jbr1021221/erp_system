<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classes;
use App\Models\FeeStructure;
use App\Models\GeneralSetting;

class AdmissionFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $classes = Classes::all();
        $year = date('Y');
        
        // Try to get academic year from settings, fallback to current year
        try {
            $year = GeneralSetting::getValue('current_session', date('Y'));
        } catch (\Exception $e) {
            $year = date('Y');
        }

        foreach ($classes as $class) {
            // Check if admission fee already exists for this class
            $exists = FeeStructure::where('class_id', $class->id)
                ->where(function($query) {
                    $query->where('fee_type', 'like', '%Admission%')
                          ->orWhere('fee_type', 'like', '%admission%');
                })
                ->exists();

            if (!$exists) {
                FeeStructure::create([
                    'class_id' => $class->id,
                    'fee_type' => 'Admission Fee',
                    'amount' => 5000.00, // Default admission fee amount
                    'frequency' => 'one_time',
                    'academic_year' => $year,
                    'is_mandatory' => true,
                ]);
                
                $this->command->info("Added Admission Fee for class: {$class->name}");
            } else {
                $this->command->info("Admission Fee already exists for class: {$class->name}");
            }
        }
    }
}
