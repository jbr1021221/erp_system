<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Classes;
use App\Models\Section;
use App\Models\FeeStructure;
use App\Models\Student;
use App\Models\StudentFeeAssignment;
use App\Models\Payment;

/**
 * OldDataMigrationSeeder  — Al-Akhirah Academy
 * ───────────────────────────────────────────────────────────────────────────
 * Run:  php artisan db:seed --class=OldDataMigrationSeeder
 *
 * Safe to run multiple times (idempotent).
 *
 * What this seeder does:
 *  0. Wipes students, payments, student_fee_assignments (keeps classes/sections/fee_structures)
 *  1. Seeds users
 *  2. Builds classMap  : old class_id (1-4)  → new classes.id
 *  3. Builds sectionMap: "oldClassId:Name"   → new sections.id
 *  4. Builds feeMap    : "newClassId:feeName"→ new fee_structures.id
 *  5. Migrates students with correct student_id format (26 + class_code + zero-padded old id)
 *  6. Migrates student_fee_assignments with discounts
 *  7. Migrates payments — splits bundled fee_details into one Payment row per fee,
 *     and correctly sets fee_structure_id, billing_month, billing_year on every row.
 */
class OldDataMigrationSeeder extends Seeder
{
    // ──────────────────────────────────────────────────────────────────────
    // OLD PROJECT — USERS
    // ──────────────────────────────────────────────────────────────────────
    private array $OLD_USERS = [
        [
            'id'       => 1,
            'name'     => 'Admin',
            'email'    => 'admin@alakhirahacademy.com',
            'password' => '$2y$12$sLV0kiNv2ixzaFWTAEt09.NSeyaYg8.YkkBViUGaEg9mx3b44BcD6',
            'role'     => 'Admin',
        ],
        [
            'id'       => 2,
            'name'     => 'Admission Officer',
            'email'    => 'admission@alakhirahacademy.com',
            'password' => '$2y$12$PALD0lVU.j2JfLTJZl3aAeN.XcEsJIO6enV4owM1ubl8x5dl8X7PO',
            'role'     => 'Admission Office',
        ],
    ];

    // ──────────────────────────────────────────────────────────────────────
    // OLD PROJECT — CLASSROOMS
    // ──────────────────────────────────────────────────────────────────────
    private array $OLD_CLASSROOMS = [
        [
            'id'            => 1,
            'name'          => 'Hifz',
            'code'          => '786',
            'admission_fee' => 15000,
            'sections'      => ['Male', 'Female'],
            'fees'          => [
                ['name' => 'Exam Fee',              'type' => 'Half-Yearly', 'amount' => 5000],
                ['name' => 'Monthly Fee (1 Shift)', 'type' => 'Monthly',     'amount' => 5000],
                ['name' => 'Monthly Fee (2 Shift)', 'type' => 'Monthly',     'amount' => 8000],
                ['name' => 'Monthly Fee (3 Shift)', 'type' => 'Monthly',     'amount' => 10000],
            ],
        ],
        [
            'id'            => 2,
            'name'          => 'Pre-Play',
            'code'          => '110',
            'admission_fee' => 70000,
            'sections'      => ['Male', 'Female'],
            'fees'          => [
                ['name' => 'Drawing & Crafting Fee', 'type' => 'Half-Yearly', 'amount' => 4000],
                ['name' => 'Exam Fee',               'type' => 'Half-Yearly', 'amount' => 3000],
                ['name' => 'Monthly Tuition Fee',    'type' => 'Monthly',     'amount' => 8000],
                ['name' => 'Hifz',                   'type' => 'Monthly',     'amount' => 2000],
            ],
        ],
        [
            'id'            => 3,
            'name'          => 'Play',
            'code'          => '111',
            'admission_fee' => 70000,
            'sections'      => ['Male', 'Female'],
            'fees'          => [
                ['name' => 'Drawing & Crafting Fee', 'type' => 'Half-Yearly', 'amount' => 4000],
                ['name' => 'Exam Fee',               'type' => 'Half-Yearly', 'amount' => 3000],
                ['name' => 'Monthly Tuition Fee',    'type' => 'Monthly',     'amount' => 8000],
                ['name' => 'Hifz',                   'type' => 'Monthly',     'amount' => 2000],
            ],
        ],
        [
            'id'            => 4,
            'name'          => 'Kg',
            'code'          => '112',
            'admission_fee' => 70000,
            'sections'      => ['Male', 'Female'],
            'fees'          => [
                ['name' => 'Drawing & Crafting Fee', 'type' => 'Half-Yearly', 'amount' => 4000],
                ['name' => 'Exam Fee',               'type' => 'Half-Yearly', 'amount' => 3000],
                ['name' => 'Monthly Tuition Fee',    'type' => 'Monthly',     'amount' => 8000],
                ['name' => 'Hifz',                   'type' => 'Monthly',     'amount' => 2000],
            ],
        ],
    ];

    // ──────────────────────────────────────────────────────────────────────
    // OLD PROJECT — STUDENTS
    // Parsed directly from students.csv
    // ──────────────────────────────────────────────────────────────────────
    private array $OLD_STUDENTS = [
        ['id'=>2,  'name'=>'MAISARAH HAMMANAH KABIR',          'class_id'=>3,'section'=>'Female','dob'=>'2017-01-12','gender'=>'Female','father_name'=>'MD HUMAYAN KABIR',           'mother_name'=>'MASKATIA AHMED',      'father_mobile'=>'01730032374','mother_mobile'=>'',            'address'=>'H NO 88,89,ROAD NO 4, MAHANAGAR HOUSING PROJECT,WEST RAMPURA,DHAKA',                   'program_type'=>'Hifz, Schooling','shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":70000,"discount":0},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000,"discount":0},{"name":"Exam Fee","type":"Half-Yearly","amount":3000,"discount":0},{"name":"Monthly Tuition Fee","type":"Monthly","amount":8000,"discount":0},{"name":"Hifz","type":"Monthly","amount":2000,"discount":0}]',
            'discounts'=>'{"Hifz":{"amount":0,"permanent":0},"Exam Fee":{"amount":0,"permanent":0},"Admission Fee":{"amount":0,"permanent":0},"Monthly Tuition Fee":{"amount":0,"permanent":0},"Drawing & Crafting Fee":{"amount":0,"permanent":0}}'],

        ['id'=>3,  'name'=>'AJWAD AHMAD',                      'class_id'=>4,'section'=>'Male',  'dob'=>'2020-07-25','gender'=>'Male',  'father_name'=>'ABRAR AHMED NABIL',          'mother_name'=>'HOSNE NAZIA TANZIM',  'father_mobile'=>'01712706827','mother_mobile'=>'01751925995', 'address'=>'Genetie Huq Garden,House - 01,Flat -10/1,1 no Ring Road,Shyamoli,Dhaka',               'program_type'=>'Schooling',      'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":70000,"discount":35000,"is_permanent":false},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":3000,"discount":0,"is_permanent":false},{"name":"Monthly Tuition Fee","type":"Monthly","amount":8000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>4,  'name'=>'Abdullah Al Ifrad',                'class_id'=>3,'section'=>'Male',  'dob'=>'2020-09-03','gender'=>'Male',  'father_name'=>'Md. Emdadul Huq Miaji',      'mother_name'=>'Nasrin Noman',        'father_mobile'=>'01752322590','mother_mobile'=>'',            'address'=>'176/6/ka, Ahmed Nagar, Ansar Camp, Mirpur-1',                                          'program_type'=>'Hifz, Schooling','shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":70000,"discount":35000,"is_permanent":false},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":3000,"discount":0,"is_permanent":false},{"name":"Monthly Tuition Fee","type":"Monthly","amount":8000,"discount":1000,"is_permanent":false},{"name":"Hifz","type":"Monthly","amount":2000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>5,  'name'=>'AMAYRA AFEEF',                     'class_id'=>1,'section'=>'Female','dob'=>'2018-12-18','gender'=>'Female','father_name'=>'AFEEF AHMED',                 'mother_name'=>'SIRAZUM MUNIRA',      'father_mobile'=>'01767820855','mother_mobile'=>'',            'address'=>'House : 125,Road :9 A, West Dhanmondi,Dhaka',                                          'program_type'=>'Hifz',           'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":5000,"discount":0,"is_permanent":false},{"name":"Monthly Fee (1 Shift)","type":"Monthly","amount":5000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>6,  'name'=>'ALFIDA KAMAL MARYAM',              'class_id'=>3,'section'=>'Female','dob'=>'2021-05-23','gender'=>'Female','father_name'=>'MD KAMAL HOSSAIN',            'mother_name'=>'MURTOZA BEGUM',       'father_mobile'=>'01713314987','mother_mobile'=>'',            'address'=>'49/1,MITALI ROAD,ZIGATALA,DHANMONDI,DHAKA',                                            'program_type'=>'Hifz, Schooling','shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":70000,"discount":35000,"is_permanent":false},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":3000,"discount":0,"is_permanent":false},{"name":"Monthly Tuition Fee","type":"Monthly","amount":8000,"discount":1500,"is_permanent":false},{"name":"Hifz","type":"Monthly","amount":2000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>7,  'name'=>'AHNAF RAHMAN FUAD',                'class_id'=>2,'section'=>'Male',  'dob'=>'2022-07-29','gender'=>'Male',  'father_name'=>'MD.MASUDUR RAHMAN',           'mother_name'=>'RUMA AHMED',          'father_mobile'=>'01952757840','mother_mobile'=>'',            'address'=>'68 SUKRABAD SUBHANBAG,DHANMONDI DHAKA',                                                'program_type'=>'Hifz, Schooling','shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":70000,"discount":62000,"is_permanent":false},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":3000,"discount":0,"is_permanent":false},{"name":"Hifz","type":"Monthly","amount":2000,"discount":1000,"is_permanent":false},{"name":"Monthly Tuition Fee","type":"Monthly","amount":8000,"discount":4000,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>8,  'name'=>'IBSAN BIN ZAMAN',                  'class_id'=>4,'section'=>'Male',  'dob'=>'2019-03-05','gender'=>'Male',  'father_name'=>'MOHAMMAD ASHRAFUZZAMAN',      'mother_name'=>'RABEYA BEGUM',        'father_mobile'=>'01770077787','mother_mobile'=>'',            'address'=>'FLAT-3B,101, INDIRA ROAD, SHERE-BANGLA NAGAR, TEJGOAN-1215',                           'program_type'=>'Schooling',      'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":70000,"discount":50000,"is_permanent":false},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":3000,"discount":0,"is_permanent":false},{"name":"Monthly Tuition Fee","type":"Monthly","amount":8000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>9,  'name'=>'ABDUR RAHMAN TAIMOOR',             'class_id'=>2,'section'=>'Male',  'dob'=>'2021-11-03','gender'=>'Male',  'father_name'=>'TANVIR AHMED',                'mother_name'=>'MALIHA MONIR BARSHA', 'father_mobile'=>'01709607557','mother_mobile'=>'',            'address'=>'RUPOSHI-PROCTIVE VILLAGE,MIRPUR-14',                                                   'program_type'=>'Hifz, Schooling','shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":70000,"discount":0,"is_permanent":false},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":3000,"discount":0,"is_permanent":false},{"name":"Hifz","type":"Monthly","amount":2000,"discount":500,"is_permanent":false},{"name":"Monthly Tuition Fee","type":"Monthly","amount":8000,"discount":3000,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>10, 'name'=>'ALEENA RAHMAN',                    'class_id'=>3,'section'=>'Female','dob'=>'2021-06-25','gender'=>'Female','father_name'=>'MD.SYDUR RAHMAN',             'mother_name'=>'NOSHIN NAWER',        'father_mobile'=>'01676086691','mother_mobile'=>'',            'address'=>'FLAT 14,HOUSE NO 300,TALI OFFICE ROAD,RAYERBAZAR,DHAKA',                              'program_type'=>'Hifz, Schooling','shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":70000,"discount":35000,"is_permanent":false},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":3000,"discount":0,"is_permanent":false},{"name":"Monthly Tuition Fee","type":"Monthly","amount":8000,"discount":2000,"is_permanent":false},{"name":"Hifz","type":"Monthly","amount":2000,"discount":1000,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>11, 'name'=>'Amanah Faryat Radiya',             'class_id'=>3,'section'=>'Female','dob'=>'2021-09-22','gender'=>'Female','father_name'=>'Anik Alamgir',                'mother_name'=>'Zarin Tasnim',        'father_mobile'=>'01749206835','mother_mobile'=>'',            'address'=>'House # 216,Road #Lake Road 14 Mohakhali DOHS,Dhaka -1206',                           'program_type'=>'Hifz, Schooling','shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":70000,"discount":0,"is_permanent":false},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":3000,"discount":0,"is_permanent":false},{"name":"Monthly Tuition Fee","type":"Monthly","amount":8000,"discount":0,"is_permanent":false},{"name":"Hifz","type":"Monthly","amount":2000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>13, 'name'=>'Ammar Yousuf Khan',                'class_id'=>1,'section'=>'Male',  'dob'=>'2022-04-03','gender'=>'Male',  'father_name'=>'Saeed Anwar Khan',            'mother_name'=>'Khadija Jahan',       'father_mobile'=>'01827854137','mother_mobile'=>'',            'address'=>'Flat D4,Rongon,18/4,Tallabag,Sobahanbag,Dhaka',                                       'program_type'=>'Hifz',           'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":5000,"discount":0,"is_permanent":false},{"name":"Monthly Fee (1 Shift)","type":"Monthly","amount":5000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>14, 'name'=>'Ammar Bin Nazib',                  'class_id'=>1,'section'=>'Male',  'dob'=>'2022-04-08','gender'=>'Male',  'father_name'=>'Nazibul Islam',               'mother_name'=>'Nabila Afrin',        'father_mobile'=>'01676765741','mother_mobile'=>'',            'address'=>'68 Shukrabad Dhanmondi Flat - 5.d',                                                    'program_type'=>'Hifz',           'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":5000,"discount":0,"is_permanent":false},{"name":"Monthly Fee (1 Shift)","type":"Monthly","amount":5000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>15, 'name'=>'MUAAZ ZAHRAN KABIR',               'class_id'=>2,'section'=>'Male',  'dob'=>'2024-01-12','gender'=>'Male',  'father_name'=>'MD HUMAYAN KABIR',            'mother_name'=>'MASKATIA AHMED',      'father_mobile'=>'01730032374','mother_mobile'=>'',            'address'=>'H NO 88,89,ROAD NO 4,MAHANAGAR HOUSING PROJECT,WEST RAMPURA,DHAKA',                   'program_type'=>'Hifz, Schooling','shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":70000,"discount":35000,"is_permanent":false},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":3000,"discount":0,"is_permanent":false},{"name":"Hifz","type":"Monthly","amount":2000,"discount":1000,"is_permanent":false},{"name":"Monthly Tuition Fee","type":"Monthly","amount":8000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>16, 'name'=>'FATIMA JANNAH',                    'class_id'=>1,'section'=>'Female','dob'=>'2013-12-18','gender'=>'Female','father_name'=>'MD.REZAUL ISLAM',             'mother_name'=>'FERDOUSI AKTER',      'father_mobile'=>'01720304132','mother_mobile'=>'',            'address'=>'HOUSE 28/A (SOUTH PRIDE),ROAD 27,DHANMONDI,DHAKA',                                    'program_type'=>'Hifz',           'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":5000,"discount":0,"is_permanent":false},{"name":"Monthly Fee (2 Shift)","type":"Monthly","amount":8000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>17, 'name'=>'MUHSINAT SAMAYRA',                 'class_id'=>2,'section'=>'Female','dob'=>'2022-02-06','gender'=>'Female','father_name'=>'MD MUHSHIUL ALAM',            'mother_name'=>'MEHERUN NESA',        'father_mobile'=>'01675764525','mother_mobile'=>'',            'address'=>'64,GREEN ROAD,DHAKA-1205',                                                             'program_type'=>'Schooling',      'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":70000,"discount":35000,"is_permanent":false},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":3000,"discount":0,"is_permanent":false},{"name":"Monthly Tuition Fee","type":"Monthly","amount":8000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>18, 'name'=>'AZEEBA RAHMAN AZRA',               'class_id'=>1,'section'=>'Female','dob'=>'2018-01-25','gender'=>'Female','father_name'=>'MOQBULUR RAHMAN',             'mother_name'=>'SHARMIN MRIDHA',      'father_mobile'=>'01706995806','mother_mobile'=>'',            'address'=>'8/A/1, ZENITH TOWER,DHANMONDI,ROAD 14 NEW,DHAKA',                                     'program_type'=>'Hifz',           'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":5000,"discount":0,"is_permanent":false},{"name":"Monthly Fee (1 Shift)","type":"Monthly","amount":5000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>19, 'name'=>'MOHAMMAD AYMAN RAHMAN ABDULLAH',   'class_id'=>1,'section'=>'Male',  'dob'=>'2016-07-22','gender'=>'Male',  'father_name'=>'MOHAMMAD MOQBULUR RAHMAN',    'mother_name'=>'SHARMIN MRIDHA',      'father_mobile'=>'01706995806','mother_mobile'=>'',            'address'=>'8/A/1, ZENITH TOWER,DHANMONDI,ROAD 14 NEW,DHAKA',                                     'program_type'=>'Hifz',           'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":7500,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":5000,"discount":0,"is_permanent":false},{"name":"Monthly Fee (1 Shift)","type":"Monthly","amount":5000,"discount":1000,"is_permanent":false}]',
            'discounts'=>'[{"fee_name":"Monthly Fee (1 Shift)","discount_type":"fixed","value":1000}]'],

        ['id'=>20, 'name'=>'MOHAMMAD ABDUR RAHMAN ASIM',       'class_id'=>1,'section'=>'Male',  'dob'=>'2020-08-14','gender'=>'Male',  'father_name'=>'MOHAMMAD MOQBULUR RAHMAN',    'mother_name'=>'SHARMIN MRIDHA',      'father_mobile'=>'01706995806','mother_mobile'=>'',            'address'=>'8/A/1, ZENITH TOWER,DHANMONDI,ROAD 14 NEW,DHAKA',                                     'program_type'=>'Hifz',           'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":7500,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":5000,"discount":0,"is_permanent":false},{"name":"Monthly Fee (1 Shift)","type":"Monthly","amount":5000,"discount":2500,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>21, 'name'=>'SYEDA FATIMA ASEEF NOURAN',        'class_id'=>1,'section'=>'Female','dob'=>'2017-08-06','gender'=>'Female','father_name'=>'SYED ASEEF ALTAF',            'mother_name'=>'FAHIMA SHAFI',        'father_mobile'=>'01748686138','mother_mobile'=>'',            'address'=>'1/3, LALMATIA BLOCK E, DHAKA',                                                         'program_type'=>'Hifz',           'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":0,"is_permanent":false},{"name":"Monthly Fee (1 Shift)","type":"Monthly","amount":5000,"discount":1000,"is_permanent":true}]',
            'discounts'=>'[{"fee_name":"Monthly Fee (1 Shift)","discount_type":"fixed","value":1000,"is_permanent":true}]'],

        ['id'=>22, 'name'=>'SYED FAIZAAN BIN ASEEF',           'class_id'=>1,'section'=>'Male',  'dob'=>'2020-10-05','gender'=>'Male',  'father_name'=>'SYED ASEEF ALTAF',            'mother_name'=>'FAHIMA SHAFI',        'father_mobile'=>'01748686138','mother_mobile'=>'',            'address'=>'1/3, LALMATIA BLOCK E, DHAKA',                                                         'program_type'=>'Hifz',           'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":15000,"is_permanent":false},{"name":"Monthly Fee (1 Shift)","type":"Monthly","amount":5000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>23, 'name'=>'SAMEEHA KAREEM',                   'class_id'=>1,'section'=>'Female','dob'=>'2015-10-12','gender'=>'Female','father_name'=>'SAFIUL KAREEM',               'mother_name'=>'SHAJEDA AKHTAR KHANAM','father_mobile'=>'01986133281','mother_mobile'=>'',           'address'=>'HOUSE NO 11/5 FLAT-E2 ROAD NO 1,KALLYANPUR,DHAKA',                                    'program_type'=>'Hifz',           'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Monthly Fee (1 Shift)","type":"Monthly","amount":5000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>24, 'name'=>'NUSAYBA SHADLEEN ANAYA',           'class_id'=>1,'section'=>'Female','dob'=>'2013-11-13','gender'=>'Female','father_name'=>'MD KHAIRUL ISLAM',            'mother_name'=>'ZAKIA AHMED',         'father_mobile'=>'01769764548','mother_mobile'=>'',            'address'=>'2H/S GOLDEN STREET FLAT-B2,RING ROAD,SHYMOLI,DHAKA',                                  'program_type'=>'Hifz',           'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":5000,"discount":0,"is_permanent":false},{"name":"Monthly Fee (3 Shift)","type":"Monthly","amount":10000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>25, 'name'=>'MD.RUSHDAN JUBRAN',                'class_id'=>1,'section'=>'Male',  'dob'=>'2017-03-23','gender'=>'Male',  'father_name'=>'MD.ARIFIN JUBAED',            'mother_name'=>'UMME KULSUM',         'father_mobile'=>'01734034947','mother_mobile'=>'',            'address'=>'FLAT NO 6B,HOUSE NO 178,ROAD NO 12/A, WEST DHANMONDI,DHAKA.',                         'program_type'=>'Hifz',           'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":5000,"discount":0,"is_permanent":false},{"name":"Monthly Fee (1 Shift)","type":"Monthly","amount":5000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>31, 'name'=>'FATEEMAH FIYANA MAHMUD',           'class_id'=>4,'section'=>'Female','dob'=>'2019-07-07','gender'=>'Female','father_name'=>'Mahmudur Rahman',             'mother_name'=>'Tahsina Khan',        'father_mobile'=>'01534548562','mother_mobile'=>'',            'address'=>'232 SULTANGANG ROAD RAYERBAZAR,DHAKA',                                                 'program_type'=>'Hifz, Schooling','shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":70000,"discount":52500,"is_permanent":false},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":3000,"discount":1500,"is_permanent":false},{"name":"Monthly Tuition Fee","type":"Monthly","amount":8000,"discount":4000,"is_permanent":false},{"name":"Hifz","type":"Monthly","amount":2000,"discount":1000,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>33, 'name'=>'Tania Binte Wahed',                'class_id'=>1,'section'=>'Female','dob'=>null,        'gender'=>'Female','father_name'=>'Md Abdul Wahed',              'mother_name'=>'Najmun Nahar',        'father_mobile'=>'01815806111','mother_mobile'=>'',            'address'=>'',                                                                                     'program_type'=>'Hifz',           'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":5000,"discount":0,"is_permanent":false},{"name":"Monthly Fee (1 Shift)","type":"Monthly","amount":5000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>35, 'name'=>'UMAR IBN ABDULLAH',                'class_id'=>2,'section'=>'Male',  'dob'=>'2023-01-16','gender'=>'Male',  'father_name'=>'ABDULLAH MAHMOOD',            'mother_name'=>'TAMANNA AFRIN',       'father_mobile'=>'01748311388','mother_mobile'=>'',            'address'=>'HOUSE NO-16/18,ROAD-02,BLOCK-B,NOBODOY HOUSUING SOCIETY,MOHAMMADPUR,DHAKA',           'program_type'=>'Hifz, Schooling','shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":70000,"discount":59000,"is_permanent":false},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":3000,"discount":0,"is_permanent":false},{"name":"Monthly Tuition Fee","type":"Monthly","amount":8000,"discount":3000,"is_permanent":true},{"name":"Hifz","type":"Monthly","amount":2000,"discount":1000,"is_permanent":true}]',
            'discounts'=>'{"Monthly Tuition Fee":{"amount":3000,"permanent":1},"Hifz":{"amount":1000,"permanent":1}}'],

        ['id'=>36, 'name'=>'ALVI AYAAN',                       'class_id'=>1,'section'=>'Male',  'dob'=>'2018-10-10','gender'=>'Male',  'father_name'=>'MAMUN ABDULLAH',              'mother_name'=>'AKHINOOR SIDDIQUA',   'father_mobile'=>'01950490016','mother_mobile'=>'',            'address'=>'HOUSE NO 8/A,8/1,ROAD NO 14(NEW),DHANMONDI DHAKA',                                    'program_type'=>'Hifz',           'shift'=>'Evening',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":5000,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":5000,"discount":0,"is_permanent":false},{"name":"Monthly Fee (1 Shift)","type":"Monthly","amount":5000,"discount":1000,"is_permanent":true}]',
            'discounts'=>'{"Monthly Fee (1 Shift)":{"amount":1000,"permanent":1}}'],

        ['id'=>37, 'name'=>'TAHRIKA TASKIN',                   'class_id'=>1,'section'=>'Female','dob'=>'2012-04-10','gender'=>'Female','father_name'=>'MUHAMMAD TOUFIQUL ISLAM',     'mother_name'=>'MAHJABIN AKTER',      'father_mobile'=>'01610600070','mother_mobile'=>'',            'address'=>'HOUSE- 28/A,ROAD 27(OLD) 16 NEW,DHANMONDI,DHAKA',                                     'program_type'=>'Hifz',           'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":5000,"discount":0,"is_permanent":false},{"name":"Monthly Fee (3 Shift)","type":"Monthly","amount":10000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>39, 'name'=>'FAATIMAH MUHAMMAD RAHIM',          'class_id'=>3,'section'=>'Female','dob'=>'2021-11-10','gender'=>'Female','father_name'=>'MOIHAMMED SAJIDUR RAHIM',     'mother_name'=>'LAILA NAZMEEN',       'father_mobile'=>'01741565108','mother_mobile'=>'',            'address'=>'HOUSE NO 99,ROAD NO 11/A,DHANMONDI R/A,DHAKA',                                        'program_type'=>'Hifz, Schooling','shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":70000,"discount":35000,"is_permanent":false},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000,"discount":0,"is_permanent":false},{"name":"Monthly Tuition Fee","type":"Monthly","amount":8000,"discount":0,"is_permanent":false},{"name":"Hifz","type":"Monthly","amount":2000,"discount":0,"is_permanent":false},{"name":"Exam Fee","type":"Half-Yearly","amount":3000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>40, 'name'=>'SAFIYYAH BINT TAREQ',              'class_id'=>1,'section'=>'Female','dob'=>'2014-07-04','gender'=>'Female','father_name'=>'TAREQ HUSSAIN',               'mother_name'=>'SUMAIYA AMIN',        'father_mobile'=>'01618304041','mother_mobile'=>'',            'address'=>'52 JAGANNATH SHAHA ROAD,AMLIGOLA,LALBAGH,DHAKA',                                       'program_type'=>'Hifz',           'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":0,"is_permanent":false},{"name":"Monthly Fee (1 Shift)","type":"Monthly","amount":5000,"discount":0,"is_permanent":false},{"name":"Monthly Fee (3 Shift)","type":"Monthly","amount":10000,"discount":0,"is_permanent":false},{"name":"Monthly Fee (2 Shift)","type":"Monthly","amount":8000,"discount":0,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>41, 'name'=>'AJMAEEN FAIEQ RAHMAN',             'class_id'=>1,'section'=>'Male',  'dob'=>'2011-10-09','gender'=>'Male',  'father_name'=>'MD.MATIUR RAHMAN',            'mother_name'=>'QUAZI SABNAM',        'father_mobile'=>'01735860609','mother_mobile'=>'',            'address'=>'HOUSE NO 146,ROAD 12/A,WEST DHANMONDI,DHAKA-1209',                                    'program_type'=>'Hifz',           'shift'=>'Evening',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":0,"is_permanent":false},{"name":"Monthly Fee (1 Shift)","type":"Monthly","amount":5000,"discount":2500,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>42, 'name'=>'AMAAN MAHROOZ RAHMAN',             'class_id'=>1,'section'=>'Male',  'dob'=>'2019-07-07','gender'=>'Male',  'father_name'=>'MD.MATIUR RAHMAN',            'mother_name'=>'QUAZI SABNAM',        'father_mobile'=>'01735860609','mother_mobile'=>'',            'address'=>'HOUSE NO 146,ROAD 12/A,WEST DHANMONDI,DHAKA-1209',                                    'program_type'=>'Hifz',           'shift'=>'Evening',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":15000,"is_permanent":false},{"name":"Monthly Fee (1 Shift)","type":"Monthly","amount":5000,"discount":2500,"is_permanent":false}]',
            'discounts'=>'[]'],

        ['id'=>43, 'name'=>'TANZINA TABASSHUM',                'class_id'=>1,'section'=>'Female','dob'=>'1984-11-19','gender'=>'Female','father_name'=>'M D FAZLUL KARIM',            'mother_name'=>'NURUN NAHAR',         'father_mobile'=>'01911346096','mother_mobile'=>'',            'address'=>'ROAD 2A,HOUSE 45,DHANMONDI DHAKA',                                                     'program_type'=>'Hifz',           'shift'=>'Morning',
            'selected_fees'=>'[{"name":"Admission Fee","type":"One Time","amount":15000,"discount":5000,"is_permanent":false},{"name":"Monthly Fee (1 Shift)","type":"Monthly","amount":5000,"discount":1000,"is_permanent":true}]',
            'discounts'=>'{"Monthly Fee (1 Shift)":{"amount":1000,"permanent":1}}'],
    ];

    // ──────────────────────────────────────────────────────────────────────
    // OLD PROJECT — PAYMENTS
    // Parsed from payments.csv. Each row is ONE old payment record.
    // fee_details is the JSON array of line items inside that payment.
    // ──────────────────────────────────────────────────────────────────────
    private array $OLD_PAYMENTS = [
        ['id'=>2,  'student_id'=>2,  'payment_mode'=>'Cash',         'payment_date'=>'2026-01-04', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":35000},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000},{"name":"Exam Fee","type":"Half-Yearly","amount":3000},{"name":"Monthly Tuition Fee - January, 26","type":"Monthly","month":"January, 26","amount":8000},{"name":"Hifz - January, 26","type":"Monthly","month":"January, 26","amount":2000}]'],
        ['id'=>3,  'student_id'=>3,  'payment_mode'=>'Cash',         'payment_date'=>'2026-01-04', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":35000},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000},{"name":"Exam Fee","type":"Half-Yearly","amount":3000},{"name":"Monthly Tuition Fee - January, 26","type":"Monthly","month":"January, 26","amount":8000}]'],
        ['id'=>4,  'student_id'=>4,  'payment_mode'=>'Cash',         'payment_date'=>'2026-01-08', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":35000},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000},{"name":"Exam Fee","type":"Half-Yearly","amount":3000},{"name":"Monthly Tuition Fee - January, 26","type":"Monthly","month":"January, 26","amount":7000},{"name":"Hifz - January, 26","type":"Monthly","month":"January, 26","amount":2000}]'],
        ['id'=>5,  'student_id'=>5,  'payment_mode'=>'Cash',         'payment_date'=>'2026-01-08', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":15000},{"name":"Monthly Fee (1 Shift) - January, 26","type":"Monthly","month":"January, 26","amount":5000}]'],
        ['id'=>6,  'student_id'=>6,  'payment_mode'=>'Cash',         'payment_date'=>'2026-01-08', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":35000},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000},{"name":"Exam Fee","type":"Half-Yearly","amount":3000},{"name":"Monthly Tuition Fee - January, 26","type":"Monthly","month":"January, 26","amount":6500},{"name":"Hifz - January, 26","type":"Monthly","month":"January, 26","amount":2000}]'],
        ['id'=>7,  'student_id'=>7,  'payment_mode'=>'Cash',         'payment_date'=>'2026-01-08', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":8000},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000},{"name":"Exam Fee","type":"Half-Yearly","amount":3000},{"name":"Hifz - January, 26","type":"Monthly","month":"January, 26","amount":1000},{"name":"Monthly Tuition Fee - January, 26","type":"Monthly","month":"January, 26","amount":4000}]'],
        ['id'=>8,  'student_id'=>8,  'payment_mode'=>'Bank',         'payment_date'=>'2026-01-08', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":20000},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000},{"name":"Exam Fee","type":"Half-Yearly","amount":3000},{"name":"Monthly Tuition Fee - January, 26","type":"Monthly","month":"January, 26","amount":8000}]'],
        ['id'=>9,  'student_id'=>9,  'payment_mode'=>'Cash',         'payment_date'=>'2026-01-07', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":22750},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000},{"name":"Exam Fee","type":"Half-Yearly","amount":3000},{"name":"Hifz - January, 26","type":"Monthly","month":"January, 26","amount":1000},{"name":"Monthly Tuition Fee - January, 26","type":"Monthly","month":"January, 26","amount":5500}]'],
        ['id'=>10, 'student_id'=>10, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-08', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":35000},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000},{"name":"Exam Fee","type":"Half-Yearly","amount":3000},{"name":"Monthly Tuition Fee - January, 26","type":"Monthly","month":"January, 26","amount":6000},{"name":"Hifz - January, 26","type":"Monthly","month":"January, 26","amount":1000}]'],
        ['id'=>11, 'student_id'=>11, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-08', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":35000},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000},{"name":"Exam Fee","type":"Half-Yearly","amount":3000},{"name":"Monthly Tuition Fee - January, 26","type":"Monthly","month":"January, 26","amount":8000},{"name":"Hifz - January, 26","type":"Monthly","month":"January, 26","amount":2000}]'],
        ['id'=>13, 'student_id'=>13, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-08', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":15000},{"name":"Exam Fee","type":"Half-Yearly","amount":5000},{"name":"Monthly Fee (1 Shift) - January, 26","type":"Monthly","month":"January, 26","amount":5000}]'],
        ['id'=>14, 'student_id'=>14, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-08', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":15000},{"name":"Exam Fee","type":"Half-Yearly","amount":5000},{"name":"Monthly Fee (1 Shift) - January, 26","type":"Monthly","month":"January, 26","amount":5000}]'],
        ['id'=>15, 'student_id'=>15, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-08', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":35000},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000},{"name":"Exam Fee","type":"Half-Yearly","amount":3000},{"name":"Hifz - January, 26","type":"Monthly","month":"January, 26","amount":1000},{"name":"Monthly Tuition Fee - January, 26","type":"Monthly","month":"January, 26","amount":8000}]'],
        ['id'=>16, 'student_id'=>16, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-08', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":15000},{"name":"Exam Fee","type":"Half-Yearly","amount":5000},{"name":"Monthly Fee (2 Shift) - January, 26","type":"Monthly","month":"January, 26","amount":8000}]'],
        ['id'=>17, 'student_id'=>17, 'payment_mode'=>'Bank',         'payment_date'=>'2026-01-11', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":35000},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000},{"name":"Exam Fee","type":"Half-Yearly","amount":3000},{"name":"Monthly Tuition Fee - January, 26","type":"Monthly","month":"January, 26","amount":8000}]'],
        ['id'=>18, 'student_id'=>18, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-11', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":15000},{"name":"Monthly Fee (1 Shift) - January, 26","type":"Monthly","month":"January, 26","amount":5000}]'],
        ['id'=>19, 'student_id'=>19, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-11', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":7500},{"name":"Monthly Fee (1 Shift) - January, 26","type":"Monthly","month":"January, 26","amount":4000}]'],
        ['id'=>20, 'student_id'=>20, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-11', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":7500},{"name":"Monthly Fee (1 Shift) - January, 26","type":"Monthly","month":"January, 26","amount":2500}]'],
        ['id'=>21, 'student_id'=>21, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-09', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - January, 26","type":"Monthly","month":"January, 26","amount":4000},{"name":"Admission Fee","type":"One Time","amount":15000}]'],
        ['id'=>22, 'student_id'=>22, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-09', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - January, 26","type":"Monthly","month":"January","year":"26","amount":3000}]'],
        ['id'=>23, 'student_id'=>23, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-11', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":15000},{"name":"Monthly Fee (1 Shift) - January, 26","type":"Monthly","month":"January, 26","amount":5000}]'],
        ['id'=>24, 'student_id'=>24, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-11', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":15000},{"name":"Exam Fee","type":"Half-Yearly","amount":5000},{"name":"Monthly Fee (3 Shift) - January, 26","type":"Monthly","month":"January, 26","amount":10000}]'],
        ['id'=>25, 'student_id'=>25, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-11', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":15000},{"name":"Monthly Fee (1 Shift) - January, 26","type":"Monthly","month":"January, 26","amount":5000}]'],
        ['id'=>33, 'student_id'=>31, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-11', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":17500},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000},{"name":"Exam Fee","type":"Half-Yearly","amount":1500},{"name":"Monthly Tuition Fee - January, 26","type":"Monthly","month":"January, 26","amount":4000},{"name":"Hifz - January, 26","type":"Monthly","month":"January, 26","amount":1000}]'],
        ['id'=>35, 'student_id'=>33, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-13', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":15000},{"name":"Monthly Fee (1 Shift) - January, 26","type":"Monthly","month":"January, 26","amount":5000}]'],
        ['id'=>37, 'student_id'=>35, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-19', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":11000},{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000},{"name":"Exam Fee","type":"Half-Yearly","amount":3000},{"name":"Monthly Tuition Fee - January, 26","type":"Monthly","month":"January, 26","amount":5000},{"name":"Hifz - January, 26","type":"Monthly","month":"January, 26","amount":1000}]'],
        ['id'=>38, 'student_id'=>36, 'payment_mode'=>'Bank',         'payment_date'=>'2026-01-20', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":10000},{"name":"Monthly Fee (1 Shift) - January, 26","type":"Monthly","month":"January, 26","amount":4000}]'],
        ['id'=>39, 'student_id'=>37, 'payment_mode'=>'Bank',         'payment_date'=>'2026-01-22', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":15000},{"name":"Exam Fee","type":"Half-Yearly","amount":5000},{"name":"Monthly Fee (3 Shift) - January, 26","type":"Monthly","month":"January, 26","amount":10000}]'],
        ['id'=>41, 'student_id'=>39, 'payment_mode'=>'Bank',         'payment_date'=>'2026-02-01', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":35000},{"name":"Drawing & Crafting Fee - 1st Half","type":"Half-Yearly","amount":4000},{"name":"Monthly Tuition Fee - February, 26","type":"Monthly","month":"February, 26","amount":8000},{"name":"Hifz - February, 26","type":"Monthly","month":"February, 26","amount":2000},{"name":"Exam Fee - 1st Half","type":"Half-Yearly","amount":3000}]'],
        ['id'=>43, 'student_id'=>40, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-01', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":15000},{"name":"Monthly Fee (3 Shift) - February, 26","type":"Monthly","month":"February, 26","amount":10000}]'],
        ['id'=>44, 'student_id'=>23, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-01', 'note'=>'', 'fee_details'=>'[{"name":"Exam Fee","type":"Half-Yearly","amount":5000}]'],
        ['id'=>46, 'student_id'=>41, 'payment_mode'=>'Cash',         'payment_date'=>'2026-01-31', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":15000}]'],
        ['id'=>47, 'student_id'=>42, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-01', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - February, 26","type":"Monthly","month":"February, 26","amount":2500}]'],
        ['id'=>48, 'student_id'=>37, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-01', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (3 Shift) - February, 26","type":"Monthly","month":"February","year":"26","amount":10000}]'],
        ['id'=>49, 'student_id'=>2,  'payment_mode'=>'Bank',         'payment_date'=>'2026-02-02', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - February, 26","type":"Monthly","month":"February","year":"26","amount":8000},{"name":"Hifz - February, 26","type":"Monthly","month":"February","year":"26","amount":2000}]'],
        ['id'=>50, 'student_id'=>15, 'payment_mode'=>'Bank',         'payment_date'=>'2026-02-02', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - February, 26","type":"Monthly","month":"February","year":"26","amount":8000},{"name":"Hifz - February, 26","type":"Monthly","month":"February","year":"26","amount":2000}]'],
        ['id'=>51, 'student_id'=>25, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-02', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - February, 26","type":"Monthly","month":"February","year":"26","amount":5000}]'],
        ['id'=>54, 'student_id'=>2,  'payment_mode'=>'Cash',         'payment_date'=>'2026-02-04', 'note'=>'duplicate-skip', 'fee_details'=>'[{"name":"Monthly Tuition Fee - February, 26","type":"Monthly","month":"February","year":"26","amount":8000},{"name":"Hifz - February, 26","type":"Monthly","month":"February","year":"26","amount":2000}]'],
        ['id'=>55, 'student_id'=>8,  'payment_mode'=>'Bank',         'payment_date'=>'2026-02-05', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - February, 26","type":"Monthly","month":"February","year":"26","amount":8000}]'],
        ['id'=>56, 'student_id'=>4,  'payment_mode'=>'Cash',         'payment_date'=>'2026-02-05', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - February, 26","type":"Monthly","month":"February","year":"26","amount":8000},{"name":"Hifz - February, 26","type":"Monthly","month":"February","year":"26","amount":2000}]'],
        ['id'=>58, 'student_id'=>24, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-05', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (3 Shift) - February, 26","type":"Monthly","month":"February","year":"26","amount":10000}]'],
        ['id'=>59, 'student_id'=>19, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-05', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - February, 26","type":"Monthly","month":"February","year":"26","amount":5000}]'],
        ['id'=>60, 'student_id'=>20, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-05', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - February, 26","type":"Monthly","month":"February","year":"26","amount":5000}]'],
        ['id'=>61, 'student_id'=>18, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-05', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - February, 26","type":"Monthly","month":"February","year":"26","amount":5000}]'],
        ['id'=>62, 'student_id'=>10, 'payment_mode'=>'Bank',         'payment_date'=>'2026-02-05', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - February, 26","type":"Monthly","month":"February","year":"26","amount":8000},{"name":"Hifz - February, 26","type":"Monthly","month":"February","year":"26","amount":2000}]'],
        ['id'=>63, 'student_id'=>13, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-08', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - February, 26","type":"Monthly","month":"February","year":"26","amount":5000}]'],
        ['id'=>64, 'student_id'=>11, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-08', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - February, 26","type":"Monthly","month":"February","year":"26","amount":8000},{"name":"Hifz - February, 26","type":"Monthly","month":"February","year":"26","amount":2000}]'],
        ['id'=>65, 'student_id'=>17, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-08', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - February, 26","type":"Monthly","month":"February","year":"26","amount":8000}]'],
        ['id'=>66, 'student_id'=>6,  'payment_mode'=>'Cash',         'payment_date'=>'2026-02-07', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - February, 26","type":"Monthly","month":"February","year":"26","amount":8000},{"name":"Hifz - February, 26","type":"Monthly","month":"February","year":"26","amount":2000}]'],
        ['id'=>67, 'student_id'=>16, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-08', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (2 Shift) - February, 26","type":"Monthly","month":"February","year":"26","amount":8000}]'],
        ['id'=>68, 'student_id'=>3,  'payment_mode'=>'Cash',         'payment_date'=>'2026-02-08', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - February, 26","type":"Monthly","month":"February","year":"26","amount":8000}]'],
        ['id'=>69, 'student_id'=>35, 'payment_mode'=>'Bank',         'payment_date'=>'2026-02-08', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - February, 26","type":"Monthly","month":"February","year":"26","amount":5000},{"name":"Hifz - February, 26","type":"Monthly","month":"February","year":"26","amount":1000}]'],
        ['id'=>70, 'student_id'=>33, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-09', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - February, 26","type":"Monthly","month":"February","year":"26","amount":5000}]'],
        ['id'=>71, 'student_id'=>16, 'payment_mode'=>'Bank',         'payment_date'=>'2026-02-08', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (2 Shift) - March, 26","type":"Monthly","month":"March","year":"26","amount":8000}]'],
        ['id'=>72, 'student_id'=>5,  'payment_mode'=>'Cash',         'payment_date'=>'2026-02-10', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - February, 26","type":"Monthly","month":"February","year":"26","amount":5000}]'],
        ['id'=>73, 'student_id'=>14, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-11', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - February, 26","type":"Monthly","month":"February","year":"26","amount":5000}]'],
        ['id'=>74, 'student_id'=>43, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-15', 'note'=>'', 'fee_details'=>'[{"name":"Admission Fee","type":"One Time","amount":10000},{"name":"Monthly Fee (1 Shift) - February, 26","type":"Monthly","month":"February, 26","amount":4000}]'],
        ['id'=>75, 'student_id'=>9,  'payment_mode'=>'Cash',         'payment_date'=>'2026-02-15', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - February, 26","type":"Monthly","month":"February","year":"26","amount":5000},{"name":"Hifz - February, 26","type":"Monthly","month":"February","year":"26","amount":1500}]'],
        ['id'=>76, 'student_id'=>21, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-15', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - February, 26","type":"Monthly","month":"February","year":"26","amount":4000}]'],
        ['id'=>77, 'student_id'=>22, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-15', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - February, 26","type":"Monthly","month":"February","year":"26","amount":4000}]'],
        ['id'=>78, 'student_id'=>7,  'payment_mode'=>'Cash',         'payment_date'=>'2026-02-09', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - February, 26","type":"Monthly","month":"February","year":"26","amount":8000},{"name":"Hifz - February, 26","type":"Monthly","month":"February","year":"26","amount":2000}]'],
        ['id'=>79, 'student_id'=>31, 'payment_mode'=>'Cash',         'payment_date'=>'2026-02-12', 'note'=>'', 'fee_details'=>'[{"name":"Drawing & Crafting Fee - February, 26","type":"Half-Yearly","month":"February","year":"26","amount":4000},{"name":"Monthly Tuition Fee - February, 26","type":"Monthly","month":"February","year":"26","amount":8000},{"name":"Hifz - February, 26","type":"Monthly","month":"February","year":"26","amount":2000}]'],
        ['id'=>80, 'student_id'=>18, 'payment_mode'=>'Cash',         'payment_date'=>'2026-03-01', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - March, 26","type":"Monthly","month":"March","year":"26","amount":5000}]'],
        ['id'=>81, 'student_id'=>25, 'payment_mode'=>'Cash',         'payment_date'=>'2026-03-02', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - March, 26","type":"Monthly","month":"March","year":"26","amount":5000}]'],
        ['id'=>82, 'student_id'=>2,  'payment_mode'=>'Bank',         'payment_date'=>'2026-03-02', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - March, 26","type":"Monthly","month":"March","year":"26","amount":8000},{"name":"Hifz - March, 26","type":"Monthly","month":"March","year":"26","amount":2000}]'],
        ['id'=>83, 'student_id'=>15, 'payment_mode'=>'Bank',         'payment_date'=>'2026-03-02', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - March, 26","type":"Monthly","month":"March","year":"26","amount":8000},{"name":"Hifz - March, 26","type":"Monthly","month":"March","year":"26","amount":2000}]'],
        ['id'=>84, 'student_id'=>13, 'payment_mode'=>'Bank',         'payment_date'=>'2026-03-02', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - March, 26","type":"Monthly","month":"March","year":"26","amount":5000}]'],
        ['id'=>85, 'student_id'=>23, 'payment_mode'=>'Cash',         'payment_date'=>'2026-03-04', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - March, 26","type":"Monthly","month":"March","year":"26","amount":5000}]'],
        ['id'=>86, 'student_id'=>37, 'payment_mode'=>'Bank',         'payment_date'=>'2026-03-04', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (3 Shift) - March, 26","type":"Monthly","month":"March","year":"26","amount":10000}]'],
        ['id'=>87, 'student_id'=>35, 'payment_mode'=>'Bank',         'payment_date'=>'2026-03-04', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - March, 26","type":"Monthly","month":"March","year":"26","amount":5000},{"name":"Hifz - March, 26","type":"Monthly","month":"March","year":"26","amount":1000}]'],
        ['id'=>88, 'student_id'=>24, 'payment_mode'=>'Bank',         'payment_date'=>'2026-03-04', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (3 Shift) - March, 26","type":"Monthly","month":"March","year":"26","amount":10000}]'],
        ['id'=>89, 'student_id'=>5,  'payment_mode'=>'Cash',         'payment_date'=>'2026-03-05', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - March, 26","type":"Monthly","month":"March","year":"26","amount":5000}]'],
        ['id'=>90, 'student_id'=>3,  'payment_mode'=>'Cash',         'payment_date'=>'2026-03-05', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - March, 26","type":"Monthly","month":"March","year":"26","amount":8000}]'],
        ['id'=>91, 'student_id'=>36, 'payment_mode'=>'Cash',         'payment_date'=>'2026-03-05', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Fee (1 Shift) - March, 26","type":"Monthly","month":"March","year":"26","amount":4000}]'],
        ['id'=>92, 'student_id'=>31, 'payment_mode'=>'Cash',         'payment_date'=>'2026-03-05', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - March, 26","type":"Monthly","month":"March","year":"26","amount":8000},{"name":"Hifz - March, 26","type":"Monthly","month":"March","year":"26","amount":2000}]'],
        ['id'=>93, 'student_id'=>8,  'payment_mode'=>'Bank',         'payment_date'=>'2026-03-05', 'note'=>'', 'fee_details'=>'[{"name":"Monthly Tuition Fee - March, 26","type":"Monthly","month":"March","year":"26","amount":8000}]'],
    ];

    // ──────────────────────────────────────────────────────────────────────
    // Internal maps (built during migration)
    // ──────────────────────────────────────────────────────────────────────
    private array $classMap   = [];  // old class_id  → new classes.id
    private array $sectionMap = [];  // "oldClassId:SectionName" → new sections.id
    private array $studentMap = [];  // old student.id → new students.id
    private array $feeMap     = [];  // "newClassId:feeName" → fee_structures.id

    private array $FREQ_MAP = [
        'One Time'    => 'one_time',
        'Monthly'     => 'monthly',
        'Quarterly'   => 'quarterly',
        'Half-Yearly' => 'half_yearly',
        'Half Yearly' => 'half_yearly',
        'Yearly'      => 'yearly',
    ];

    private array $MONTH_MAP = [
        'January'=>1, 'February'=>2, 'March'=>3,    'April'=>4,
        'May'=>5,     'June'=>6,     'July'=>7,      'August'=>8,
        'September'=>9,'October'=>10,'November'=>11, 'December'=>12,
    ];

    private array $PAYMENT_METHOD_MAP = [
        'cash'          => 'cash',
        'Cash'          => 'cash',
        'bank_transfer' => 'bank_transfer',
        'Bank Transfer' => 'bank_transfer',
        'Bank'          => 'bank_transfer',
        'bank'          => 'bank_transfer',
        'online'        => 'online',
        'Online'        => 'online',
        'cheque'        => 'cheque',
        'Cheque'        => 'cheque',
        'card'          => 'card',
        'Card'          => 'card',
    ];

    // ──────────────────────────────────────────────────────────────────────

    public function run(): void
    {
        $this->command->info('═══════════════════════════════════════════════');
        $this->command->info('  Al-Akhirah Academy — Old Data Migration');
        $this->command->info('═══════════════════════════════════════════════');

        DB::transaction(function () {
            $this->step0_wipeData();
            $this->step1_migrateUsers();
            $this->step2_buildClassMap();
            $this->step3_buildSectionMap();
            $this->step4_buildFeeMap();
            $this->step5_migrateStudents();
            $this->step6_migrateFeeAssignments();
            $this->step7_migratePayments();
        });

        $this->command->info('');
        $this->command->info('✓ Migration complete!');
        $this->command->info('');
        $this->command->info('Verify with:');
        $this->command->info('  SELECT student_id FROM students LIMIT 5;');
        $this->command->info('  SELECT COUNT(*) FROM payments WHERE fee_structure_id IS NULL;');
        $this->command->info('  SELECT COUNT(*) FROM payments WHERE billing_month IS NULL AND fee_type NOT LIKE "%Admission%";');
    }

    // ──────────────────────────────────────────────────────────────────────
    // STEP 0 — Wipe migrated data (keep classes, sections, fee_structures)
    // ──────────────────────────────────────────────────────────────────────
    private function step0_wipeData(): void
    {
        $this->command->info('[0/7] Wiping existing student/payment data…');

        StudentFeeAssignment::query()->delete();
        Payment::query()->delete();
        // Use forceDelete to also remove soft-deleted students
        Student::withTrashed()->forceDelete();

        $this->command->info('  → Wiped. Classes, sections, fee_structures preserved.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // STEP 1 — Users
    // ──────────────────────────────────────────────────────────────────────
    private function step1_migrateUsers(): void
    {
        $this->command->info('[1/7] Migrating users…');

        foreach ($this->OLD_USERS as $old) {
            $user = User::updateOrCreate(
                ['email' => $old['email']],
                [
                    'name'     => $old['name'],
                    'password' => $old['password'],
                    'status'   => 'active',
                ]
            );

            if (!empty($old['role'])) {
                \Spatie\Permission\Models\Role::firstOrCreate(
                    ['name' => $old['role'], 'guard_name' => 'web']
                );
                $user->syncRoles([$old['role']]);
            }
        }

        $this->command->info('  → ' . count($this->OLD_USERS) . ' users ready.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // STEP 2 — Build classMap  (old classroom id → new classes.id)
    // Classes already exist in DB with codes 786/110/111/112.
    // ──────────────────────────────────────────────────────────────────────
    private function step2_buildClassMap(): void
    {
        $this->command->info('[2/7] Building class map…');

        foreach ($this->OLD_CLASSROOMS as $old) {
            $class = Classes::updateOrCreate(
                ['code' => $old['code']],
                [
                    'name'          => $old['name'],
                    'capacity'      => 40,
                    'academic_year' => '2026',
                    'is_active'     => true,
                ]
            );
            $this->classMap[$old['id']] = $class->id;
        }

        $this->command->info('  → classMap built: ' . json_encode($this->classMap));
    }

    // ──────────────────────────────────────────────────────────────────────
    // STEP 3 — Build sectionMap ("oldClassId:Name" → sections.id)
    // ──────────────────────────────────────────────────────────────────────
    private function step3_buildSectionMap(): void
    {
        $this->command->info('[3/7] Building section map…');

        foreach ($this->OLD_CLASSROOMS as $old) {
            $newClassId = $this->classMap[$old['id']] ?? null;
            if (!$newClassId) continue;

            foreach ($old['sections'] as $sectionName) {
                $section = Section::firstOrCreate(
                    ['class_id' => $newClassId, 'name' => $sectionName],
                    ['capacity' => 40]
                );
                $this->sectionMap[$old['id'] . ':' . $sectionName] = $section->id;
            }
        }

        $this->command->info('  → sectionMap built with ' . count($this->sectionMap) . ' entries.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // STEP 4 — Build feeMap ("newClassId:feeName" → fee_structures.id)
    // ──────────────────────────────────────────────────────────────────────
    private function step4_buildFeeMap(): void
    {
        $this->command->info('[4/7] Building fee structure map…');

        $count = 0;
        foreach ($this->OLD_CLASSROOMS as $old) {
            $newClassId = $this->classMap[$old['id']] ?? null;
            if (!$newClassId) continue;

            // Regular fees
            foreach ($old['fees'] as $fee) {
                $frequency = $this->FREQ_MAP[$fee['type'] ?? ''] ?? 'one_time';

                $fs = FeeStructure::firstOrCreate(
                    [
                        'class_id'  => $newClassId,
                        'fee_type'  => $fee['name'],
                        'frequency' => $frequency,
                    ],
                    [
                        'amount'        => $fee['amount'],
                        'academic_year' => '2026',
                        'is_mandatory'  => true,
                    ]
                );

                $this->feeMap[$newClassId . ':' . $fee['name']] = $fs->id;
                $count++;
            }

            // Admission fee
            if (!empty($old['admission_fee'])) {
                $fs = FeeStructure::firstOrCreate(
                    [
                        'class_id'  => $newClassId,
                        'fee_type'  => 'Admission Fee',
                        'frequency' => 'one_time',
                    ],
                    [
                        'amount'        => $old['admission_fee'],
                        'academic_year' => '2026',
                        'is_mandatory'  => true,
                    ]
                );

                $this->feeMap[$newClassId . ':Admission Fee'] = $fs->id;
                $count++;
            }
        }

        $this->command->info("  → feeMap built with {$count} entries.");
    }

    // ──────────────────────────────────────────────────────────────────────
    // STEP 5 — Students
    //
    // student_id format: '26' + class_code + str_pad(old_id, 3, '0', STR_PAD_LEFT)
    // e.g. old_id=6, class=Play(111) → '26111006'
    // ──────────────────────────────────────────────────────────────────────
    private function step5_migrateStudents(): void
    {
        $this->command->info('[5/7] Migrating students…');

        // Pre-build earliest payment date per old student_id for enrollment_date
        $enrollmentDates = [];
        foreach ($this->OLD_PAYMENTS as $p) {
            $sid = $p['student_id'];
            $date = $p['payment_date'];
            if (!isset($enrollmentDates[$sid]) || $date < $enrollmentDates[$sid]) {
                $enrollmentDates[$sid] = $date;
            }
        }

        // ── Hardcoded student_id map from old system screenshots ──────────
        // The suffix is the sequential enrollment rank within each class
        // (including deleted students), NOT the database id.
        // Duplicates (26111001, 26111002) existed in the old system — preserved as-is.
        $studentIdMap = [
            2  => '26111001',  // MAISARAH HAMMANAH KABIR       - Play  (enrolled first, keeps original)
            3  => '26112001',  // AJWAD AHMAD                   - Kg
            4  => '26111002',  // Abdullah Al Ifrad             - Play  (enrolled first, keeps original)
            5  => '26786001',  // AMAYRA AFEEF                  - Hifz
            6  => '26111003',  // ALFIDA KAMAL MARYAM           - Play  (was duplicate 26111001, assigned next available)
            7  => '26110003',  // AHNAF RAHMAN FUAD             - Pre-Play
            8  => '26112002',  // IBSAN BIN ZAMAN               - Kg
            9  => '26110004',  // ABDUR RAHMAN TAIMOOR          - Pre-Play
            10 => '26111004',  // ALEENA RAHMAN                 - Play  (was duplicate 26111002, assigned next available)
            11 => '26110005',  // Amanah Faryat Radiya          - Pre-Play
            13 => '26786002',  // Ammar Yousuf Khan             - Hifz
            14 => '26786003',  // Ammar Bin Nazib               - Hifz
            15 => '26110006',  // MUAAZ ZAHRAN KABIR            - Pre-Play
            16 => '26786004',  // FATIMA JANNAH                 - Hifz
            17 => '26110007',  // MUHSINAT SAMAYRA              - Pre-Play
            18 => '26786005',  // AZEEBA RAHMAN AZRA            - Hifz
            19 => '26786006',  // MOHAMMAD AYMAN RAHMAN ABDULLA - Hifz
            20 => '26786007',  // MOHAMMAD ABDUR RAHMAN ASIM    - Hifz
            21 => '26786008',  // SYEDA FATIMA ASEEF NOURAN     - Hifz
            22 => '26786009',  // SYED FAIZAAN BIN ASEEF        - Hifz
            23 => '26786010',  // SAMEEHA KAREEM                - Hifz
            24 => '26786011',  // NUSAYBA SHADLEEN ANAYA        - Hifz
            25 => '26786012',  // MD.RUSHDAN JUBRAN             - Hifz
            31 => '26112003',  // FATEEMAH FIYANA MAHMUD        - Kg
            33 => '26786013',  // Tania Binte Wahed             - Hifz
            35 => '26110008',  // UMAR IBN ABDULLAH             - Pre-Play
            36 => '26786014',  // ALVI AYAAN                    - Hifz
            37 => '26786015',  // TAHRIKA TASKIN                - Hifz
            39 => '26111006',  // FAATIMAH MUHAMMAD RAHIM       - Play
            40 => '26786016',  // SAFIYYAH BINT TAREQ           - Hifz
            41 => '26786017',  // AJMAEEN FAIEQ RAHMAN          - Hifz
            42 => '26786018',  // AMAAN MAHROOZ RAHMAN          - Hifz
            43 => '26786019',  // TANZINA TABASSHUM             - Hifz
        ];

        foreach ($this->OLD_STUDENTS as $old) {
            $newClassId   = $this->classMap[$old['class_id']] ?? null;
            $newSectionId = $this->sectionMap[$old['class_id'] . ':' . ($old['section'] ?? '')] ?? null;

            // ── Correct student_id from hardcoded map ──────────────────
            $studentId = $studentIdMap[$old['id']] ?? null;
            if (!$studentId) {
                $this->command->error("  ✗ No student_id mapped for old id={$old['id']} ({$old['name']}) — skipping.");
                continue;
            }

            // ── Name split ────────────────────────────────────────────
            $nameParts = explode(' ', trim($old['name']), 2);
            $firstName = $nameParts[0];
            $lastName  = $nameParts[1] ?? '';

            // ── Enrollment date from earliest payment ─────────────────
            $enrollmentDate = $enrollmentDates[$old['id']] ?? '2026-01-01';

            $student = Student::updateOrCreate(
                ['student_id' => $studentId],
                [
                    'first_name'      => $firstName,
                    'last_name'       => $lastName,
                    'date_of_birth'   => !empty($old['dob']) ? $old['dob'] : '2000-01-01',
                    'gender'          => strtolower($old['gender'] ?? 'male'),
                    'address'         => $old['address'] ?? null,
                    'guardian_name'   => $old['father_name'] ?? '',
                    'guardian_phone'  => $old['father_mobile'] ?? '',
                    'father_name'     => $old['father_name'] ?? '',
                    'mother_name'     => $old['mother_name'] ?? '',
                    'mother_mobile'   => $old['mother_mobile'] ?? '',
                    'phone'           => $old['father_mobile'] ?? '',
                    'program_type'    => $old['program_type'] ?? '',
                    'shift'           => $old['shift'] ?? 'Morning',
                    'class_id'        => $newClassId,
                    'section_id'      => $newSectionId,
                    'enrollment_date' => $enrollmentDate,
                    'status'          => 'active',
                ]
            );

            $this->studentMap[$old['id']] = $student->id;
        }

        $this->command->info('  → ' . count($this->OLD_STUDENTS) . ' students migrated.');
    }

    // ──────────────────────────────────────────────────────────────────────
    // STEP 6 — StudentFeeAssignments
    // ──────────────────────────────────────────────────────────────────────
    private function step6_migrateFeeAssignments(): void
    {
        $this->command->info('[6/7] Migrating fee assignments…');

        $count = 0;
        $skipped = 0;

        foreach ($this->OLD_STUDENTS as $old) {
            $newStudentId = $this->studentMap[$old['id']] ?? null;
            $newClassId   = $this->classMap[$old['class_id']] ?? null;
            if (!$newStudentId || !$newClassId) continue;

            $selectedFees = json_decode($old['selected_fees'] ?? '[]', true) ?? [];
            $rawDiscounts = json_decode($old['discounts']     ?? '[]', true) ?? [];

            // Normalise discounts into keyed map by fee name
            $discountMap = [];
            if (!empty($rawDiscounts)) {
                if (is_array($rawDiscounts) && array_is_list($rawDiscounts)) {
                    // Old array format: [{"fee_name":"X","discount_type":"fixed","value":Y}]
                    foreach ($rawDiscounts as $d) {
                        $key = $d['fee_name'] ?? '';
                        if ($key === '') continue;
                        $discountMap[$key] = [
                            'discount_type' => $d['discount_type'] ?? 'fixed',
                            'value'         => (float)($d['value'] ?? 0),
                            'is_permanent'  => (bool)($d['is_permanent'] ?? false),
                        ];
                    }
                } else {
                    // New object format: {"FeeName":{"amount":X,"permanent":0|1}}
                    foreach ($rawDiscounts as $feeName => $d) {
                        $discountMap[$feeName] = [
                            'discount_type' => 'fixed',
                            'value'         => (float)($d['amount'] ?? 0),
                            'is_permanent'  => (bool)($d['permanent'] ?? false),
                        ];
                    }
                }
            }

            foreach ($selectedFees as $sel) {
                $feeName  = $sel['name'] ?? $sel['fee_name'] ?? '';
                $feeKey   = $newClassId . ':' . $feeName;
                $newFeeId = $this->feeMap[$feeKey] ?? null;

                if (!$newFeeId) {
                    $this->command->warn("    ⚠ Fee not found: [{$feeKey}] for student id={$old['id']} — skipping.");
                    $skipped++;
                    continue;
                }

                $disc         = $discountMap[$feeName] ?? [];
                $inlineDisc   = (float)($sel['discount'] ?? 0);
                $discountType = match ($disc['discount_type'] ?? 'none') {
                    'percentage' => 'percentage',
                    'fixed'      => 'fixed',
                    default      => $inlineDisc > 0 ? 'fixed' : 'none',
                };
                $discountValue = (float)($disc['value'] ?? $inlineDisc);
                $isPermanent   = (bool)($disc['is_permanent'] ?? $sel['is_permanent'] ?? false);

                StudentFeeAssignment::firstOrCreate(
                    [
                        'student_id'       => $newStudentId,
                        'fee_structure_id' => $newFeeId,
                    ],
                    [
                        'discount_type'  => $discountType,
                        'discount_value' => $discountValue,
                        'is_permanent'   => $isPermanent,
                    ]
                );

                $count++;
            }
        }

        $this->command->info("  → {$count} fee assignments migrated. {$skipped} skipped (fee not found).");
    }

    // ──────────────────────────────────────────────────────────────────────
    // STEP 7 — Payments
    //
    // Each old payment row may contain multiple fee_details items.
    // These are SPLIT into individual Payment rows — one per fee line.
    //
    // CRITICAL fields set on every row:
    //   • fee_structure_id  — looked up via feeMap
    //   • billing_month     — integer 1-12 (null for one-time fees)
    //   • billing_year      — e.g. 2026
    //
    // billing_month for half-yearly fees:
    //   All 2026 data is from Jan-Mar → H1 → billing_month = 6
    // ──────────────────────────────────────────────────────────────────────
    private function step7_migratePayments(): void
    {
        $this->command->info('[7/7] Migrating payments…');

        $count   = 0;
        $skipped = 0;

        // Track seen receipt+feetype combos to handle true duplicates
        // (e.g. payment ids 49 & 54 — same student, same month, same fee)
        $seen = [];

        foreach ($this->OLD_PAYMENTS as $old) {
            $newStudentId = $this->studentMap[$old['student_id']] ?? null;
            if (!$newStudentId) {
                $this->command->warn("    ⚠ Student old_id={$old['student_id']} not found — skipping payment id={$old['id']}.");
                $skipped++;
                continue;
            }

            // Get the student's new class_id for fee lookup
            $student    = Student::find($newStudentId);
            $newClassId = $student?->class_id;

            $paymentMethod = $this->PAYMENT_METHOD_MAP[$old['payment_mode'] ?? 'Cash'] ?? 'cash';
            $paymentDate   = $old['payment_date'];
            $paymentYear   = (int) Carbon::parse($paymentDate)->format('Y');

            $feeDetails = json_decode($old['fee_details'] ?? '[]', true) ?? [];
            if (empty($feeDetails)) {
                $skipped++;
                continue;
            }

            // Receipt matches old system: '26' + MMDD + padded old payment id
            // e.g. id=93, date=2026-03-05 → '260305093'
            $mmdd        = Carbon::parse($paymentDate)->format('md');
            $receiptBase = '26' . $mmdd . str_pad($old['id'], 3, '0', STR_PAD_LEFT);

            foreach ($feeDetails as $idx => $item) {
                $rawName = $item['name'] ?? $item['fee_name'] ?? 'Unknown Fee';

                // Strip " - Month, YY" or " - Month YY" suffix to get clean fee name
                // e.g. "Monthly Tuition Fee - January, 26" → "Monthly Tuition Fee"
                // e.g. "Drawing & Crafting Fee - 1st Half"  → "Drawing & Crafting Fee"
                $cleanName = preg_replace('/\s*-\s*.+$/', '', $rawName);
                $cleanName = trim($cleanName);

                $itemType = $item['type'] ?? '';
                $amount   = (float)($item['amount'] ?? 0);

                if ($amount <= 0) {
                    // Skip zero-amount lines (can happen with data anomalies)
                    continue;
                }

                // ── Determine billing_month and billing_year ───────────
                $billingMonth = null;
                $billingYear  = $paymentYear;

                if ($itemType === 'One Time') {
                    // Admission / one-time fees have no billing month
                    $billingMonth = null;
                } elseif ($itemType === 'Half-Yearly') {
                    // H1 = billing_month 6, H2 = billing_month 12
                    // All current data is Jan-Mar 2026 → H1
                    $billingMonth = 6;
                    $billingYear  = $paymentYear;
                } else {
                    // Monthly — extract month from item or fall back to payment date
                    $monthStr = $item['month'] ?? null;

                    if ($monthStr) {
                        // month field may be "January, 26" or "January" or "February"
                        // Extract just the month name (first word)
                        $monthName = trim(explode(',', $monthStr)[0]);
                        $billingMonth = $this->MONTH_MAP[$monthName] ?? null;

                        // Year: prefer item['year'] field, else from month suffix, else payment date
                        if (!empty($item['year'])) {
                            $yr = trim($item['year']);
                            $billingYear = strlen($yr) === 2 ? (int)('20' . $yr) : (int)$yr;
                        } elseif (strpos($monthStr, ',') !== false) {
                            // "January, 26" format — extract year after comma
                            $parts = explode(',', $monthStr);
                            $yr = trim($parts[1] ?? '');
                            if ($yr !== '') {
                                $billingYear = strlen($yr) === 2 ? (int)('20' . $yr) : (int)$yr;
                            }
                        }
                    } else {
                        // No month in item — use payment_date month
                        $billingMonth = (int) Carbon::parse($paymentDate)->format('n');
                    }
                }

                // ── Lookup fee_structure_id ────────────────────────────
                $feeStructureId = null;
                if ($newClassId) {
                    $feeKey = $newClassId . ':' . $cleanName;
                    $feeStructureId = $this->feeMap[$feeKey] ?? null;

                    if (!$feeStructureId) {
                        $this->command->warn("    ⚠ Fee not in map: [{$feeKey}] (payment id={$old['id']}, item='{$rawName}') — inserting with null fee_structure_id.");
                    }
                }

                // ── Receipt number — unique per fee line ───────────────
                $receiptNumber = $receiptBase;

                // ── Duplicate guard (same student + fee_structure + month + year) ─
                $dupeKey = "{$newStudentId}:{$feeStructureId}:{$billingMonth}:{$billingYear}:{$cleanName}";
                if (isset($seen[$dupeKey])) {
                    $this->command->warn("    ⚠ Duplicate detected for student={$old['student_id']}, fee='{$cleanName}', month={$billingMonth}/{$billingYear} — skipping payment id={$old['id']}.");
                    $skipped++;
                    continue;
                }
                $seen[$dupeKey] = true;

                Payment::firstOrCreate(
                    ['receipt_number' => $receiptNumber, 'fee_type' => $cleanName],
                    [
                        'student_id'       => $newStudentId,
                        'fee_structure_id' => $feeStructureId,
                        'fee_type'         => $cleanName,
                        'amount'           => $amount,
                        'payment_method'   => $paymentMethod,
                        'payment_date'     => $paymentDate,
                        'billing_month'    => $billingMonth,
                        'billing_year'     => $billingYear,
                        'remarks'          => !empty($old['note']) ? $old['note'] : 'Migrated from old system',
                        'status'           => 'completed',
                        'received_by'      => 1,
                    ]
                );

                $count++;
            }
        }

        $this->command->info("  → {$count} payment rows migrated. {$skipped} skipped.");
    }

    // ──────────────────────────────────────────────────────────────────────
    // Helper — strip month suffix from fee name
    // "Monthly Tuition Fee - January, 26" → "Monthly Tuition Fee"
    // "Drawing & Crafting Fee - 1st Half" → "Drawing & Crafting Fee"
    // ──────────────────────────────────────────────────────────────────────
    private function stripMonthSuffix(string $name): string
    {
        return trim(preg_replace('/\s*-\s*.+$/', '', $name));
    }
}