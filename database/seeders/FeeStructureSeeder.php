<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Classes;
use App\Models\FeeStructure;
use App\Models\GeneralSetting;

class FeeStructureSeeder extends Seeder
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
        
        // Try to get academic year from settings
        try {
            $year = GeneralSetting::getValue('current_session', date('Y'));
        } catch (\Exception $e) {
            $year = date('Y');
        }

        // Define standard fees
        $standardFees = [
            [
                'fee_type' => 'Tuition Fee',
                'frequency' => 'monthly',
                'base_amount' => 1000, // Base amount, will increase with class level
                'is_mandatory' => true,
            ],
            [
                'fee_type' => 'Exam Fee',
                'frequency' => 'quarterly',
                'base_amount' => 500,
                'is_mandatory' => true,
            ],
            [
                'fee_type' => 'Sports & Cultural Fee',
                'frequency' => 'half_yearly',
                'base_amount' => 300,
                'is_mandatory' => false,
            ],
            [
                'fee_type' => 'Session Fee',
                'frequency' => 'yearly',
                'base_amount' => 2000,
                'is_mandatory' => true,
            ],
            [
                'fee_type' => 'ID Card & Diary',
                'frequency' => 'one_time',
                'base_amount' => 500,
                'is_mandatory' => true,
            ],
            // Ensure Admission Fee is also covered (even if done by previous seeder)
            [
                'fee_type' => 'Admission Fee',
                'frequency' => 'one_time',
                'base_amount' => 5000,
                'is_mandatory' => true,
            ],
        ];

        foreach ($classes as $index => $class) {
            // Calculate a multiplier based on class (assuming higher ID/Index = higher class)
            // Or just add 100 * index to base amount
            $classMultiplier = ($index + 1) * 100;

            foreach ($standardFees as $fee) {
                // Check if this fee type already exists for this class
                $exists = FeeStructure::where('class_id', $class->id)
                    ->where('fee_type', $fee['fee_type'])
                    ->exists();

                if (!$exists) {
                    $amount = $fee['base_amount'];
                    
                    // Slightly increase tuition for higher classes
                    if ($fee['fee_type'] == 'Tuition Fee') {
                        $amount += $classMultiplier;
                    }

                    FeeStructure::create([
                        'class_id' => $class->id,
                        'fee_type' => $fee['fee_type'],
                        'amount' => $amount,
                        'frequency' => $fee['frequency'],
                        'academic_year' => $year,
                        'is_mandatory' => $fee['is_mandatory'],
                    ]);
                    
                    $this->command->info("Added {$fee['fee_type']} ({$fee['frequency']}) for {$class->name}");
                }
            }
        }
    }
}
