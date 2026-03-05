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
 * OldDataMigrationSeeder
 *
 * Migrates data from the OLD project (classrooms/fees JSON / flat payments)
 * to the NEW project schema (fee_structures table / normalized payments).
 *
 * KEY DIFFERENCES FIXED vs. original seeder
 * ─────────────────────────────────────────
 * 1. NEW selected_fees uses key "name" not "fee_name"  → handled with null-coalescing
 * 2. NEW selected_fees has "type" field (One Time / Monthly / Half-Yearly …)
 *    → $FREQ_MAP now includes "One Time" → "one_time"
 * 3. NEW discounts field is a KEYED OBJECT  {"FeeName":{"amount":X,"permanent":Y}}
 *    OLD discounts field is an ARRAY        [{"fee_name":"X","discount_type":"fixed","value":Y}]
 *    → step6 auto-detects format via array_is_list()
 * 4. NEW payments fee_details items use "name" key (not "fee_name")
 *    and monthly fee names include " - Month, YY" suffix  (e.g. "Monthly Tuition Fee - January, 26")
 *    → step7 strips the suffix before looking up feeMap / writing fee_type
 * 5. NEW payments have no "billing_month" / "billing_year" columns
 *    → removed those columns; month info comes from the fee_details suffix
 * 6. Payment.payment_method enum values are lowercase in new schema
 *    → paymentMethodMap normalises "Cash" → "cash", "Bank Transfer" → "bank_transfer" etc.
 * 7. FeeStructure has no "billing_month" column – Payment stores fee_type (string)
 *    → step7 writes fee_type directly from the fee detail name (stripped of month suffix)
 *
 * HOW TO USE:
 *   php artisan db:seed --class=OldDataMigrationSeeder
 */
class OldDataMigrationSeeder extends Seeder
{
    // ─────────────────────────────────────────────────────────────────────
    // OLD PROJECT DATA
    // ─────────────────────────────────────────────────────────────────────

    private array $OLD_USERS = [
        [
            'id'       => 1,
            'name'     => 'Admin',
            'email'    => 'admin@alakhirahacademy.com',
            'password' => '$2y$12$sLV0kiNv2ixzaFWTAEt09.NSeyaYg8.YkkBViUGaEg9mx3b44BcD6',
            'role'     => 'admin',
        ],
        [
            'id'       => 2,
            'name'     => 'Admisssion officer',
            'email'    => 'admission@alakhirahacademy.com',
            'password' => '$2y$12$PALD0lVU.j2JfLTJZl3aAeN.XcEsJIO6enV4owM1ubl8x5dl8X7PO',
            'role'     => 'admission_office',
        ],
    ];

    private array $OLD_CLASSROOMS = [
        [
            'id'                       => 1,
            'name'                     => 'Hifz',
            'class_id'                 => '786',
            'max_students_per_section' => 40,
            'admission_fee'            => 15000,
            'sections'                 => '["Male","Female"]',
            'fees'                     => '[{"name":"Exam Fee","type":"Half-Yearly","amount":5000},{"name":"Monthly Fee (1 Shift)","type":"Monthly","amount":5000},{"name":"Monthly Fee (2 Shift)","type":"Monthly","amount":8000},{"name":"Monthly Fee (3 Shift)","type":"Monthly","amount":10000}]',
        ],
        [
            'id'                       => 2,
            'name'                     => 'Pre-Play',
            'class_id'                 => '110',
            'max_students_per_section' => 40,
            'admission_fee'            => 70000,
            'sections'                 => '["Male","Female"]',
            'fees'                     => '[{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000},{"name":"Exam Fee","type":"Half-Yearly","amount":3000},{"name":"Monthly Tuition Fee","type":"Monthly","amount":8000},{"name":"Hifz","type":"Monthly","amount":2000}]',
        ],
        [
            'id'                       => 3,
            'name'                     => 'Play',
            'class_id'                 => '111',
            'max_students_per_section' => 40,
            'admission_fee'            => 70000,
            'sections'                 => '["Male","Female"]',
            'fees'                     => '[{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000},{"name":"Exam Fee","type":"Half-Yearly","amount":3000},{"name":"Monthly Tuition Fee","type":"Monthly","amount":8000},{"name":"Hifz","type":"Monthly","amount":2000}]',
        ],
        [
            'id'                       => 4,
            'name'                     => 'Kg',
            'class_id'                 => '112',
            'max_students_per_section' => 40,
            'admission_fee'            => 70000,
            'sections'                 => '["Male","Female"]',
            'fees'                     => '[{"name":"Drawing & Crafting Fee","type":"Half-Yearly","amount":4000},{"name":"Exam Fee","type":"Half-Yearly","amount":3000},{"name":"Monthly Tuition Fee","type":"Monthly","amount":8000},{"name":"Hifz","type":"Monthly","amount":2000}]',
        ],
    ];

    private array $OLD_STUDENTS = [
        ['id'=>2,  'student_id'=>'MIG00002','name'=>'MAISARAH HAMMANAH KABIR','dob'=>'2017-01-12','gender'=>'female','class_id'=>3,'section'=>'Female','father_name'=>'MD HUMAYAN KABIR','mother_name'=>'MASKATIA AHMED','father_mobile'=>'01730032374','mother_mobile'=>'','address'=>'H NO 88,89,ROAD NO 4, MAHANAGAR HOUSING PROJECT,WEST RAMPURA,DHAKA','program_type'=>'Hifz, Schooling','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-04','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":70000,"discount":0},{"fee_name":"Drawing & Crafting Fee","amount":4000,"discount":0},{"fee_name":"Exam Fee","amount":3000,"discount":0},{"fee_name":"Monthly Tuition Fee","amount":8000,"discount":0},{"fee_name":"Hifz","amount":2000,"discount":0}]'],
        ['id'=>3,  'student_id'=>'MIG00003','name'=>'AJWAD AHMAD','dob'=>'2020-07-25','gender'=>'male','class_id'=>4,'section'=>'Male','father_name'=>'ABRAR AHMED NABIL','mother_name'=>'HOSNE NAZIA TANZIM','father_mobile'=>'01712706827','mother_mobile'=>'01751925995','address'=>'Genetie Huq Garden,House - 01,Flat -10/1,1 no Ring Road,Shyamoli,Dhaka','program_type'=>'Schooling','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-04','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":70000,"discount":35000},{"fee_name":"Drawing & Crafting Fee","amount":4000,"discount":0},{"fee_name":"Exam Fee","amount":3000,"discount":0},{"fee_name":"Monthly Tuition Fee","amount":8000,"discount":0}]'],
        ['id'=>4,  'student_id'=>'MIG00004','name'=>'Abdullah Al Ifrad','dob'=>'2020-09-03','gender'=>'male','class_id'=>3,'section'=>'Male','father_name'=>'Md. Emdadul Huq Miaji','mother_name'=>'Nasrin Noman','father_mobile'=>'01752322590','mother_mobile'=>'','address'=>'176/6/ka, Ahmed Nagar, Ansar Camp, Mirpur-1','program_type'=>'Hifz, Schooling','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-08','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":70000,"discount":35000},{"fee_name":"Drawing & Crafting Fee","amount":4000,"discount":0},{"fee_name":"Exam Fee","amount":3000,"discount":0},{"fee_name":"Monthly Tuition Fee","amount":8000,"discount":1000,"is_permanent":false},{"fee_name":"Hifz","amount":2000,"discount":0}]'],
        ['id'=>5,  'student_id'=>'MIG00005','name'=>'AMAYRA AFEEF','dob'=>'2018-12-18','gender'=>'female','class_id'=>1,'section'=>'Female','father_name'=>'AFEEF AHMED','mother_name'=>'SIRAZUM MUNIRA','father_mobile'=>'01767820855','mother_mobile'=>'','address'=>'House : 125,Road :9 A, West Dhanmondi,Dhaka','program_type'=>'Hifz','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-08','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":0},{"fee_name":"Exam Fee","amount":5000,"discount":0},{"fee_name":"Monthly Fee (1 Shift)","amount":5000,"discount":0}]'],
        ['id'=>6,  'student_id'=>'MIG00006','name'=>'ALFIDA KAMAL MARYAM','dob'=>'2021-05-23','gender'=>'female','class_id'=>3,'section'=>'Female','father_name'=>'MD KAMAL HOSSAIN','mother_name'=>'MURTOZA BEGUM','father_mobile'=>'01713314987','mother_mobile'=>'','address'=>'49/1,MITALI ROAD,ZIGATALA,DHANMONDI,DHAKA','program_type'=>'Hifz, Schooling','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-08','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":70000,"discount":35000},{"fee_name":"Drawing & Crafting Fee","amount":4000,"discount":0},{"fee_name":"Exam Fee","amount":3000,"discount":0},{"fee_name":"Monthly Tuition Fee","amount":8000,"discount":1500,"is_permanent":false},{"fee_name":"Hifz","amount":2000,"discount":0}]'],
        ['id'=>7,  'student_id'=>'MIG00007','name'=>'AHNAF RAHMAN FUAD','dob'=>'2022-07-29','gender'=>'male','class_id'=>2,'section'=>'Male','father_name'=>'MD.MASUDUR RAHMAN','mother_name'=>'RUMA AHMED','father_mobile'=>'01952757840','mother_mobile'=>'','address'=>'68 SUKRABAD SUBHANBAG,DHANMONDI DHAKA','program_type'=>'Hifz, Schooling','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-08','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":70000,"discount":62000},{"fee_name":"Drawing & Crafting Fee","amount":4000,"discount":0},{"fee_name":"Exam Fee","amount":3000,"discount":0},{"fee_name":"Hifz","amount":2000,"discount":1000,"is_permanent":false},{"fee_name":"Monthly Tuition Fee","amount":8000,"discount":4000,"is_permanent":false}]'],
        ['id'=>8,  'student_id'=>'MIG00008','name'=>'IBSAN BIN ZAMAN','dob'=>'2019-03-05','gender'=>'male','class_id'=>4,'section'=>'Male','father_name'=>'MOHAMMAD ASHRAFUZZAMAN','mother_name'=>'RABEYA BEGUM','father_mobile'=>'01770077787','mother_mobile'=>'','address'=>'FLAT-3B,101, INDIRA ROAD, SHERE-BANGLA NAGAR, TEJGOAN-1215','program_type'=>'Schooling','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-08','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":70000,"discount":50000},{"fee_name":"Drawing & Crafting Fee","amount":4000,"discount":0},{"fee_name":"Exam Fee","amount":3000,"discount":0},{"fee_name":"Monthly Tuition Fee","amount":8000,"discount":0}]'],
        ['id'=>9,  'student_id'=>'MIG00009','name'=>'ABDUR RAHMAN TAIMOOR','dob'=>'2021-11-03','gender'=>'male','class_id'=>2,'section'=>'Male','father_name'=>'TANVIR AHMED','mother_name'=>'MALIHA MONIR BARSHA','father_mobile'=>'01709607557','mother_mobile'=>'','address'=>'RUPOSHI-PROCTIVE VILLAGE,MIRPUR-14','program_type'=>'Hifz, Schooling','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-07','status'=>'active','discounts'=>'[{"fee_name":"Hifz","discount_type":"fixed","value":500},{"fee_name":"Monthly Tuition Fee","discount_type":"fixed","value":3000}]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":70000,"discount":0},{"fee_name":"Drawing & Crafting Fee","amount":4000,"discount":0},{"fee_name":"Exam Fee","amount":3000,"discount":0},{"fee_name":"Hifz","amount":2000,"discount":500},{"fee_name":"Monthly Tuition Fee","amount":8000,"discount":3000}]'],
        ['id'=>10, 'student_id'=>'MIG00010','name'=>'ALEENA RAHMAN','dob'=>'2021-06-25','gender'=>'female','class_id'=>3,'section'=>'Female','father_name'=>'MD.SYDUR RAHMAN','mother_name'=>'NOSHIN NAWER','father_mobile'=>'01676086691','mother_mobile'=>'','address'=>'FLAT 14,HOUSE NO 300,TALI OFFICE ROAD,RAYERBAZAR,DHAKA','program_type'=>'Hifz, Schooling','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-08','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":70000,"discount":35000},{"fee_name":"Drawing & Crafting Fee","amount":4000,"discount":0},{"fee_name":"Exam Fee","amount":3000,"discount":0},{"fee_name":"Monthly Tuition Fee","amount":8000,"discount":2000,"is_permanent":false},{"fee_name":"Hifz","amount":2000,"discount":1000,"is_permanent":false}]'],
        ['id'=>11, 'student_id'=>'MIG00011','name'=>'Amanah Faryat Radiya','dob'=>'2021-09-22','gender'=>'female','class_id'=>3,'section'=>'Female','father_name'=>'Anik Alamgir','mother_name'=>'Zarin Tasnim','father_mobile'=>'01749206835','mother_mobile'=>'','address'=>'House # 216,Road #Lake Road 14 Mohakhali DOHS,Dhaka -1206','program_type'=>'Hifz, Schooling','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-08','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":70000,"discount":0},{"fee_name":"Drawing & Crafting Fee","amount":4000,"discount":0},{"fee_name":"Exam Fee","amount":3000,"discount":0},{"fee_name":"Monthly Tuition Fee","amount":8000,"discount":0},{"fee_name":"Hifz","amount":2000,"discount":0}]'],
        ['id'=>13, 'student_id'=>'MIG00013','name'=>'Ammar Yousuf Khan','dob'=>'2022-04-03','gender'=>'male','class_id'=>1,'section'=>'Male','father_name'=>'Saeed Anwar Khan','mother_name'=>'Khadija Jahan','father_mobile'=>'01827854137','mother_mobile'=>'','address'=>'Flat D4,Rongon,18/4,Tallabag,Sobahanbag,Dhaka','program_type'=>'Hifz','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-08','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":0},{"fee_name":"Exam Fee","amount":5000,"discount":0},{"fee_name":"Monthly Fee (1 Shift)","amount":5000,"discount":0}]'],
        ['id'=>14, 'student_id'=>'MIG00014','name'=>'Ammar Bin Nazib','dob'=>'2022-04-08','gender'=>'male','class_id'=>1,'section'=>'Male','father_name'=>'Nazibul Islam','mother_name'=>'Nabila Afrin','father_mobile'=>'01676765741','mother_mobile'=>'','address'=>'68 Shukrabad Dhanmondi Flat - 5.d','program_type'=>'Hifz','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-08','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":0},{"fee_name":"Exam Fee","amount":5000,"discount":0},{"fee_name":"Monthly Fee (1 Shift)","amount":5000,"discount":0}]'],
        ['id'=>15, 'student_id'=>'MIG00015','name'=>'MUAAZ ZAHRAN KABIR','dob'=>'2024-01-12','gender'=>'male','class_id'=>2,'section'=>'Male','father_name'=>'MD HUMAYAN KABIR','mother_name'=>'MASKATIA AHMED','father_mobile'=>'01730032374','mother_mobile'=>'','address'=>'H NO 88,89,ROAD NO 4,MAHANAGAR HOUSING PROJECT,WEST RAMPURA,DHAKA','program_type'=>'Hifz, Schooling','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-08','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":70000,"discount":35000},{"fee_name":"Drawing & Crafting Fee","amount":4000,"discount":0},{"fee_name":"Exam Fee","amount":3000,"discount":0},{"fee_name":"Hifz","amount":2000,"discount":1000,"is_permanent":false},{"fee_name":"Monthly Tuition Fee","amount":8000,"discount":0}]'],
        ['id'=>16, 'student_id'=>'MIG00016','name'=>'FATIMA JANNAH','dob'=>'2013-12-18','gender'=>'female','class_id'=>1,'section'=>'Female','father_name'=>'MD.REZAUL ISLAM','mother_name'=>'FERDOUSI AKTER','father_mobile'=>'01720304132','mother_mobile'=>'','address'=>'HOUSE 28/A (SOUTH PRIDE),ROAD 27,DHANMONDI,DHAKA','program_type'=>'Hifz','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-08','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":0},{"fee_name":"Exam Fee","amount":5000,"discount":0},{"fee_name":"Monthly Fee (2 Shift)","amount":8000,"discount":0}]'],
        ['id'=>17, 'student_id'=>'MIG00017','name'=>'MUHSINAT SAMAYRA','dob'=>'2022-02-06','gender'=>'female','class_id'=>2,'section'=>'Female','father_name'=>'MD MUHSHIUL ALAM','mother_name'=>'MEHERUN NESA','father_mobile'=>'01675764525','mother_mobile'=>'','address'=>'64,GREEN ROAD,DHAKA-1205','program_type'=>'Schooling','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-11','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":70000,"discount":35000},{"fee_name":"Drawing & Crafting Fee","amount":4000,"discount":0},{"fee_name":"Exam Fee","amount":3000,"discount":0},{"fee_name":"Monthly Tuition Fee","amount":8000,"discount":0}]'],
        ['id'=>18, 'student_id'=>'MIG00018','name'=>'AZEEBA RAHMAN AZRA','dob'=>'2018-01-25','gender'=>'female','class_id'=>1,'section'=>'Female','father_name'=>'MOQBULUR RAHMAN','mother_name'=>'SHARMIN MRIDHA','father_mobile'=>'01706995806','mother_mobile'=>'','address'=>'8/A/1, ZENITH TOWER,DHANMONDI,ROAD 14 NEW,DHAKA','program_type'=>'Hifz','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-11','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":0},{"fee_name":"Exam Fee","amount":5000,"discount":0},{"fee_name":"Monthly Fee (1 Shift)","amount":5000,"discount":0}]'],
        ['id'=>19, 'student_id'=>'MIG00019','name'=>'MOHAMMAD AYMAN RAHMAN ABDULLAH','dob'=>'2016-07-22','gender'=>'male','class_id'=>1,'section'=>'Male','father_name'=>'MOHAMMAD MOQBULUR RAHMAN','mother_name'=>'SHARMIN MRIDHA','father_mobile'=>'01706995806','mother_mobile'=>'','address'=>'8/A/1, ZENITH TOWER,DHANMONDI,ROAD 14 NEW,DHAKA','program_type'=>'Hifz','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-11','status'=>'active','discounts'=>'[{"fee_name":"Monthly Fee (1 Shift)","discount_type":"fixed","value":1000}]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":7500},{"fee_name":"Exam Fee","amount":5000,"discount":0},{"fee_name":"Monthly Fee (1 Shift)","amount":5000,"discount":1000}]'],
        ['id'=>20, 'student_id'=>'MIG00020','name'=>'MOHAMMAD ABDUR RAHMAN ASIM','dob'=>'2020-08-14','gender'=>'male','class_id'=>1,'section'=>'Male','father_name'=>'MOHAMMAD MOQBULUR RAHMAN','mother_name'=>'SHARMIN MRIDHA','father_mobile'=>'01706995806','mother_mobile'=>'','address'=>'8/A/1, ZENITH TOWER,DHANMONDI,ROAD 14 NEW,DHAKA','program_type'=>'Hifz','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-11','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":7500},{"fee_name":"Exam Fee","amount":5000,"discount":0},{"fee_name":"Monthly Fee (1 Shift)","amount":5000,"discount":2500}]'],
        ['id'=>21, 'student_id'=>'MIG00021','name'=>'SYEDA FATIMA ASEEF NOURAN','dob'=>'2017-08-06','gender'=>'female','class_id'=>1,'section'=>'Female','father_name'=>'SYED ASEEF ALTAF','mother_name'=>'FAHIMA SHAFI','father_mobile'=>'01748686138','mother_mobile'=>'','address'=>'1/3, LALMATIA BLOCK E, DHAKA','program_type'=>'Hifz','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-09','status'=>'active','discounts'=>'[{"fee_name":"Monthly Fee (1 Shift)","discount_type":"fixed","value":1000,"is_permanent":true}]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":0},{"fee_name":"Monthly Fee (1 Shift)","amount":5000,"discount":1000}]'],
        ['id'=>22, 'student_id'=>'MIG00022','name'=>'SYED FAIZAAN BIN ASEEF','dob'=>'2020-10-05','gender'=>'male','class_id'=>1,'section'=>'Male','father_name'=>'SYED ASEEF ALTAF','mother_name'=>'FAHIMA SHAFI','father_mobile'=>'01748686138','mother_mobile'=>'','address'=>'1/3, LALMATIA BLOCK E, DHAKA','program_type'=>'Hifz','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-09','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":15000},{"fee_name":"Monthly Fee (1 Shift)","amount":5000,"discount":0}]'],
        ['id'=>23, 'student_id'=>'MIG00023','name'=>'SAMEEHA KAREEM','dob'=>'2015-10-12','gender'=>'female','class_id'=>1,'section'=>'Female','father_name'=>'SAFIUL KAREEM','mother_name'=>'SHAJEDA AKHTAR KHANAM','father_mobile'=>'01986133281','mother_mobile'=>'','address'=>'HOUSE NO 11/5 FLAT-E2 ROAD NO 1,KALLYANPUR,DHAKA','program_type'=>'Hifz','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-11','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":5000,"discount":0}]'],
        ['id'=>24, 'student_id'=>'MIG00024','name'=>'NUSAYBA SHADLEEN ANAYA','dob'=>'2013-11-13','gender'=>'female','class_id'=>1,'section'=>'Female','father_name'=>'MD KHAIRUL ISLAM','mother_name'=>'ZAKIA AHMED','father_mobile'=>'01769764548','mother_mobile'=>'','address'=>'2H/S GOLDEN STREET FLAT-B2,RING ROAD,SHYMOLI,DHAKA','program_type'=>'Hifz','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-11','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":0},{"fee_name":"Exam Fee","amount":5000,"discount":0},{"fee_name":"Monthly Fee (3 Shift)","amount":10000,"discount":0}]'],
        ['id'=>25, 'student_id'=>'MIG00025','name'=>'MD.RUSHDAN JUBRAN','dob'=>'2017-03-23','gender'=>'male','class_id'=>1,'section'=>'Male','father_name'=>'MD.ARIFIN JUBAED','mother_name'=>'UMME KULSUM','father_mobile'=>'01734034947','mother_mobile'=>'','address'=>'FLAT NO 6B,HOUSE NO 178,ROAD NO 12/A, WEST DHANMONDI,DHAKA.','program_type'=>'Hifz','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-11','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":0},{"fee_name":"Exam Fee","amount":5000,"discount":0},{"fee_name":"Monthly Fee (1 Shift)","amount":5000,"discount":0}]'],
        ['id'=>31, 'student_id'=>'MIG00031','name'=>'FATEEMAH FIYANA MAHMUD','dob'=>'2019-07-07','gender'=>'female','class_id'=>4,'section'=>'Female','father_name'=>'Mahmudur Rahman','mother_name'=>'Tahsina Khan','father_mobile'=>'01534548562','mother_mobile'=>'','address'=>'232 SULTANGANG ROAD RAYERBAZAR,DHAKA','program_type'=>'Hifz, Schooling','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-11','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":70000,"discount":52500},{"fee_name":"Drawing & Crafting Fee","amount":4000,"discount":0},{"fee_name":"Exam Fee","amount":3000,"discount":1500,"is_permanent":false},{"fee_name":"Monthly Tuition Fee","amount":8000,"discount":4000,"is_permanent":false},{"fee_name":"Hifz","amount":2000,"discount":1000,"is_permanent":false}]'],
        ['id'=>33, 'student_id'=>'MIG00033','name'=>'Tania Binte Wahed','dob'=>null,'gender'=>'female','class_id'=>1,'section'=>'Female','father_name'=>'Md Abdul Wahed','mother_name'=>'Najmun Nahar','father_mobile'=>'01815806111','mother_mobile'=>'','address'=>'','program_type'=>'Hifz','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-13','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":0},{"fee_name":"Exam Fee","amount":5000,"discount":0},{"fee_name":"Monthly Fee (1 Shift)","amount":5000,"discount":0}]'],
        ['id'=>35, 'student_id'=>'MIG00035','name'=>'UMAR IBN ABDULLAH','dob'=>'2023-01-16','gender'=>'male','class_id'=>2,'section'=>'Male','father_name'=>'ABDULLAH MAHMOOD','mother_name'=>'TAMANNA AFRIN','father_mobile'=>'01748311388','mother_mobile'=>'','address'=>'HOUSE NO-16/18,ROAD-02,BLOCK-B,NOBODOY HOUSUING SOCIETY,MOHAMMADPUR,DHAKA','program_type'=>'Hifz, Schooling','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-19','status'=>'active','discounts'=>'[{"fee_name":"Monthly Tuition Fee","discount_type":"fixed","value":3000,"is_permanent":true},{"fee_name":"Hifz","discount_type":"fixed","value":1000,"is_permanent":true}]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":70000,"discount":59000},{"fee_name":"Drawing & Crafting Fee","amount":4000,"discount":0},{"fee_name":"Exam Fee","amount":3000,"discount":0},{"fee_name":"Monthly Tuition Fee","amount":8000,"discount":3000,"is_permanent":true},{"fee_name":"Hifz","amount":2000,"discount":1000,"is_permanent":true}]'],
        ['id'=>36, 'student_id'=>'MIG00036','name'=>'ALVI AYAAN','dob'=>'2018-10-10','gender'=>'male','class_id'=>1,'section'=>'Male','father_name'=>'MAMUN ABDULLAH','mother_name'=>'AKHINOOR SIDDIQUA','father_mobile'=>'01950490016','mother_mobile'=>'','address'=>'HOUSE NO 8/A,8/1,ROAD NO 14(NEW),DHANMONDI DHAKA','program_type'=>'Hifz','shift'=>'Evening','nid_file_path'=>null,'enrollment_date'=>'2026-01-20','status'=>'active','discounts'=>'[{"fee_name":"Monthly Fee (1 Shift)","discount_type":"fixed","value":1000,"is_permanent":true}]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":5000},{"fee_name":"Exam Fee","amount":5000,"discount":0},{"fee_name":"Monthly Fee (1 Shift)","amount":5000,"discount":1000,"is_permanent":true}]'],
        ['id'=>37, 'student_id'=>'MIG00037','name'=>'TAHRIKA TASKIN','dob'=>'2012-04-10','gender'=>'female','class_id'=>1,'section'=>'Female','father_name'=>'MUHAMMAD TOUFIQUL ISLAM','mother_name'=>'MAHJABIN AKTER','father_mobile'=>'01610600070','mother_mobile'=>'','address'=>'HOUSE- 28/A,ROAD 27(OLD) 16 NEW,DHANMONDI,DHAKA','program_type'=>'Hifz','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-01-22','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":0},{"fee_name":"Exam Fee","amount":5000,"discount":0},{"fee_name":"Monthly Fee (3 Shift)","amount":10000,"discount":0}]'],
        ['id'=>39, 'student_id'=>'MIG00039','name'=>'FAATIMAH MUHAMMAD RAHIM','dob'=>'2021-11-10','gender'=>'female','class_id'=>3,'section'=>'Female','father_name'=>'MOIHAMMED SAJIDUR RAHIM','mother_name'=>'LAILA NAZMEEN','father_mobile'=>'01741565108','mother_mobile'=>'','address'=>'HOUSE NO 99,ROAD NO 11/A,DHANMONDI R/A,DHAKA','program_type'=>'Hifz, Schooling','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-02-01','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":70000,"discount":35000},{"fee_name":"Drawing & Crafting Fee","amount":4000,"discount":0},{"fee_name":"Monthly Tuition Fee","amount":8000,"discount":0},{"fee_name":"Hifz","amount":2000,"discount":0},{"fee_name":"Exam Fee","amount":3000,"discount":0}]'],
        ['id'=>40, 'student_id'=>'MIG00040','name'=>'SAFIYYAH BINT TAREQ','dob'=>'2014-07-04','gender'=>'female','class_id'=>1,'section'=>'Female','father_name'=>'TAREQ HUSSAIN','mother_name'=>'SUMAIYA AMIN','father_mobile'=>'01618304041','mother_mobile'=>'','address'=>'52 JAGANNATH SHAHA ROAD,AMLIGOLA,LALBAGH,DHAKA','program_type'=>'Hifz','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-02-01','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":0},{"fee_name":"Monthly Fee (1 Shift)","amount":5000,"discount":0},{"fee_name":"Monthly Fee (3 Shift)","amount":10000,"discount":0},{"fee_name":"Monthly Fee (2 Shift)","amount":8000,"discount":0}]'],
        ['id'=>41, 'student_id'=>'MIG00041','name'=>'AJMAEEN FAIEQ RAHMAN','dob'=>'2011-10-09','gender'=>'male','class_id'=>1,'section'=>'Male','father_name'=>'MD.MATIUR RAHMAN','mother_name'=>'QUAZI SABNAM','father_mobile'=>'01735860609','mother_mobile'=>'','address'=>'HOUSE NO 146,ROAD 12/A,WEST DHANMONDI,DHAKA-1209','program_type'=>'Hifz','shift'=>'Evening','nid_file_path'=>null,'enrollment_date'=>'2026-01-31','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":0},{"fee_name":"Monthly Fee (1 Shift)","amount":5000,"discount":2500}]'],
        ['id'=>42, 'student_id'=>'MIG00042','name'=>'AMAAN MAHROOZ RAHMAN','dob'=>'2019-07-07','gender'=>'male','class_id'=>1,'section'=>'Male','father_name'=>'MD.MATIUR RAHMAN','mother_name'=>'QUAZI SABNAM','father_mobile'=>'01735860609','mother_mobile'=>'','address'=>'HOUSE NO 146,ROAD 12/A,WEST DHANMONDI,DHAKA-1209','program_type'=>'Hifz','shift'=>'Evening','nid_file_path'=>null,'enrollment_date'=>'2026-02-01','status'=>'active','discounts'=>'[]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":15000},{"fee_name":"Monthly Fee (1 Shift)","amount":5000,"discount":2500}]'],
        ['id'=>43, 'student_id'=>'MIG00043','name'=>'TANZINA TABASSHUM','dob'=>'1984-11-19','gender'=>'female','class_id'=>1,'section'=>'Female','father_name'=>'M D FAZLUL KARIM','mother_name'=>'NURUN NAHAR','father_mobile'=>'01911346096','mother_mobile'=>'','address'=>'ROAD 2A,HOUSE 45,DHANMONDI DHAKA','program_type'=>'Hifz','shift'=>'Morning','nid_file_path'=>null,'enrollment_date'=>'2026-02-15','status'=>'active','discounts'=>'[{"fee_name":"Monthly Fee (1 Shift)","discount_type":"fixed","value":1000,"is_permanent":true}]','selected_fees'=>'[{"fee_name":"Admission Fee","amount":15000,"discount":5000},{"fee_name":"Monthly Fee (1 Shift)","amount":5000,"discount":1000,"is_permanent":true}]'],
    ];

    private array $OLD_PAYMENTS = [
        ['id'=>2,  'student_id'=>2,  'payment_mode'=>'cash',          'payment_date'=>'2026-01-04','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":35000},{"fee_name":"Drawing & Crafting Fee","amount":4000},{"fee_name":"Exam Fee","amount":3000},{"fee_name":"Monthly Tuition Fee","amount":8000},{"fee_name":"Hifz","amount":2000}]'],
        ['id'=>3,  'student_id'=>3,  'payment_mode'=>'cash',          'payment_date'=>'2026-01-04','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":35000},{"fee_name":"Drawing & Crafting Fee","amount":4000},{"fee_name":"Exam Fee","amount":3000},{"fee_name":"Monthly Tuition Fee","amount":8000}]'],
        ['id'=>4,  'student_id'=>4,  'payment_mode'=>'cash',          'payment_date'=>'2026-01-08','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":35000},{"fee_name":"Drawing & Crafting Fee","amount":4000},{"fee_name":"Exam Fee","amount":3000},{"fee_name":"Monthly Tuition Fee","amount":7000},{"fee_name":"Hifz","amount":2000}]'],
        ['id'=>5,  'student_id'=>5,  'payment_mode'=>'cash',          'payment_date'=>'2026-01-08','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":15000},{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>6,  'student_id'=>6,  'payment_mode'=>'cash',          'payment_date'=>'2026-01-08','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":35000},{"fee_name":"Drawing & Crafting Fee","amount":4000},{"fee_name":"Exam Fee","amount":3000},{"fee_name":"Monthly Tuition Fee","amount":6500},{"fee_name":"Hifz","amount":2000}]'],
        ['id'=>7,  'student_id'=>7,  'payment_mode'=>'cash',          'payment_date'=>'2026-01-08','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":8000},{"fee_name":"Drawing & Crafting Fee","amount":4000},{"fee_name":"Exam Fee","amount":3000},{"fee_name":"Hifz","amount":1000},{"fee_name":"Monthly Tuition Fee","amount":4000}]'],
        ['id'=>8,  'student_id'=>8,  'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-01-08','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":20000},{"fee_name":"Drawing & Crafting Fee","amount":4000},{"fee_name":"Exam Fee","amount":3000},{"fee_name":"Monthly Tuition Fee","amount":8000}]'],
        ['id'=>9,  'student_id'=>9,  'payment_mode'=>'cash',          'payment_date'=>'2026-01-07','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":22750},{"fee_name":"Drawing & Crafting Fee","amount":4000},{"fee_name":"Exam Fee","amount":3000},{"fee_name":"Hifz","amount":1000},{"fee_name":"Monthly Tuition Fee","amount":5500}]'],
        ['id'=>10, 'student_id'=>10, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-08','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":35000},{"fee_name":"Drawing & Crafting Fee","amount":4000},{"fee_name":"Exam Fee","amount":3000},{"fee_name":"Monthly Tuition Fee","amount":6000},{"fee_name":"Hifz","amount":1000}]'],
        ['id'=>11, 'student_id'=>11, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-08','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":35000},{"fee_name":"Drawing & Crafting Fee","amount":4000},{"fee_name":"Exam Fee","amount":3000},{"fee_name":"Monthly Tuition Fee","amount":8000},{"fee_name":"Hifz","amount":2000}]'],
        ['id'=>13, 'student_id'=>13, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-08','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":15000},{"fee_name":"Exam Fee","amount":5000},{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>14, 'student_id'=>14, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-08','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":15000},{"fee_name":"Exam Fee","amount":5000},{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>15, 'student_id'=>15, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-08','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":35000},{"fee_name":"Drawing & Crafting Fee","amount":4000},{"fee_name":"Exam Fee","amount":3000},{"fee_name":"Hifz","amount":1000},{"fee_name":"Monthly Tuition Fee","amount":8000}]'],
        ['id'=>16, 'student_id'=>16, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-08','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":15000},{"fee_name":"Exam Fee","amount":5000},{"fee_name":"Monthly Fee (2 Shift)","amount":8000}]'],
        ['id'=>17, 'student_id'=>17, 'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-01-11','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":35000},{"fee_name":"Drawing & Crafting Fee","amount":4000},{"fee_name":"Exam Fee","amount":3000},{"fee_name":"Monthly Tuition Fee","amount":8000}]'],
        ['id'=>18, 'student_id'=>18, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-11','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":15000},{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>19, 'student_id'=>19, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-11','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":7500},{"fee_name":"Monthly Fee (1 Shift)","amount":4000}]'],
        ['id'=>20, 'student_id'=>20, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-11','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":7500},{"fee_name":"Monthly Fee (1 Shift)","amount":2500}]'],
        ['id'=>21, 'student_id'=>21, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-09','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":4000},{"fee_name":"Admission Fee","amount":15000}]'],
        ['id'=>22, 'student_id'=>22, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-09','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":3000}]'],
        ['id'=>23, 'student_id'=>23, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-11','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":15000},{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>24, 'student_id'=>24, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-11','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":15000},{"fee_name":"Exam Fee","amount":5000},{"fee_name":"Monthly Fee (3 Shift)","amount":10000}]'],
        ['id'=>25, 'student_id'=>25, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-11','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":15000},{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>33, 'student_id'=>31, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-11','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":17500},{"fee_name":"Drawing & Crafting Fee","amount":4000},{"fee_name":"Exam Fee","amount":1500},{"fee_name":"Monthly Tuition Fee","amount":4000},{"fee_name":"Hifz","amount":1000}]'],
        ['id'=>35, 'student_id'=>33, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-13','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":15000},{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>37, 'student_id'=>35, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-19','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":11000},{"fee_name":"Drawing & Crafting Fee","amount":4000},{"fee_name":"Exam Fee","amount":3000},{"fee_name":"Monthly Tuition Fee","amount":5000},{"fee_name":"Hifz","amount":1000}]'],
        ['id'=>38, 'student_id'=>36, 'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-01-20','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":10000},{"fee_name":"Monthly Fee (1 Shift)","amount":4000}]'],
        ['id'=>39, 'student_id'=>37, 'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-01-22','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":15000},{"fee_name":"Exam Fee","amount":5000},{"fee_name":"Monthly Fee (3 Shift)","amount":10000}]'],
        ['id'=>41, 'student_id'=>39, 'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-02-01','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Admission Fee","amount":35000},{"fee_name":"Drawing & Crafting Fee","amount":4000},{"fee_name":"Monthly Tuition Fee","amount":8000},{"fee_name":"Hifz","amount":2000},{"fee_name":"Exam Fee","amount":3000}]'],
        ['id'=>43, 'student_id'=>40, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-01','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Admission Fee","amount":15000},{"fee_name":"Monthly Fee (3 Shift)","amount":10000}]'],
        ['id'=>44, 'student_id'=>23, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-01','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Exam Fee","amount":5000}]'],
        ['id'=>45, 'student_id'=>23, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-01','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Exam Fee","amount":5000}]'],
        ['id'=>46, 'student_id'=>41, 'payment_mode'=>'cash',          'payment_date'=>'2026-01-31','note'=>'','month'=>'January',  'fee_details'=>'[{"fee_name":"Admission Fee","amount":15000}]'],
        ['id'=>47, 'student_id'=>42, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-01','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":2500}]'],
        ['id'=>48, 'student_id'=>37, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-01','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Fee (3 Shift)","amount":10000}]'],
        ['id'=>49, 'student_id'=>2,  'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-02-02','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":8000},{"fee_name":"Hifz","amount":2000}]'],
        ['id'=>50, 'student_id'=>15, 'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-02-02','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":8000},{"fee_name":"Hifz","amount":2000}]'],
        ['id'=>51, 'student_id'=>25, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-02','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>54, 'student_id'=>2,  'payment_mode'=>'cash',          'payment_date'=>'2026-02-04','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":8000},{"fee_name":"Hifz","amount":2000}]'],
        ['id'=>55, 'student_id'=>8,  'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-02-05','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":8000}]'],
        ['id'=>56, 'student_id'=>4,  'payment_mode'=>'cash',          'payment_date'=>'2026-02-05','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":8000},{"fee_name":"Hifz","amount":2000}]'],
        ['id'=>58, 'student_id'=>24, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-05','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Fee (3 Shift)","amount":10000}]'],
        ['id'=>59, 'student_id'=>19, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-05','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>60, 'student_id'=>20, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-05','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":2500}]'],
        ['id'=>61, 'student_id'=>18, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-05','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":4000}]'],
        ['id'=>62, 'student_id'=>10, 'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-02-05','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":8000},{"fee_name":"Hifz","amount":2000}]'],
        ['id'=>63, 'student_id'=>13, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-08','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>64, 'student_id'=>11, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-08','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":8000},{"fee_name":"Hifz","amount":2000}]'],
        ['id'=>65, 'student_id'=>17, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-08','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":8000}]'],
        ['id'=>66, 'student_id'=>6,  'payment_mode'=>'cash',          'payment_date'=>'2026-02-07','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":8000},{"fee_name":"Hifz","amount":2000}]'],
        ['id'=>67, 'student_id'=>16, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-08','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Fee (2 Shift)","amount":8000}]'],
        ['id'=>68, 'student_id'=>3,  'payment_mode'=>'cash',          'payment_date'=>'2026-02-08','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":8000}]'],
        ['id'=>69, 'student_id'=>35, 'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-02-08','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":5000},{"fee_name":"Hifz","amount":1000}]'],
        ['id'=>70, 'student_id'=>33, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-09','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>71, 'student_id'=>16, 'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-02-08','note'=>'','month'=>'March',    'fee_details'=>'[{"fee_name":"Monthly Fee (2 Shift)","amount":8000}]'],
        ['id'=>72, 'student_id'=>5,  'payment_mode'=>'cash',          'payment_date'=>'2026-02-10','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>73, 'student_id'=>14, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-11','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>74, 'student_id'=>43, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-15','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Admission Fee","amount":10000},{"fee_name":"Monthly Fee (1 Shift)","amount":4000}]'],
        ['id'=>75, 'student_id'=>9,  'payment_mode'=>'cash',          'payment_date'=>'2026-02-15','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":5000},{"fee_name":"Hifz","amount":1500}]'],
        ['id'=>76, 'student_id'=>21, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-15','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":4000}]'],
        ['id'=>77, 'student_id'=>22, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-15','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":4000}]'],
        ['id'=>78, 'student_id'=>7,  'payment_mode'=>'cash',          'payment_date'=>'2026-02-09','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":8000},{"fee_name":"Hifz","amount":2000}]'],
        ['id'=>79, 'student_id'=>31, 'payment_mode'=>'cash',          'payment_date'=>'2026-02-12','note'=>'','month'=>'February', 'fee_details'=>'[{"fee_name":"Drawing & Crafting Fee","amount":4000},{"fee_name":"Monthly Tuition Fee","amount":8000},{"fee_name":"Hifz","amount":2000}]'],
        ['id'=>80, 'student_id'=>18, 'payment_mode'=>'cash',          'payment_date'=>'2026-03-01','note'=>'','month'=>'March',    'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>81, 'student_id'=>25, 'payment_mode'=>'cash',          'payment_date'=>'2026-03-02','note'=>'','month'=>'March',    'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>82, 'student_id'=>2,  'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-03-02','note'=>'','month'=>'March',    'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":8000},{"fee_name":"Hifz","amount":2000}]'],
        ['id'=>83, 'student_id'=>15, 'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-03-02','note'=>'','month'=>'March',    'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":8000},{"fee_name":"Hifz","amount":2000}]'],
        ['id'=>84, 'student_id'=>13, 'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-03-02','note'=>'','month'=>'March',    'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>85, 'student_id'=>23, 'payment_mode'=>'cash',          'payment_date'=>'2026-03-04','note'=>'','month'=>'March',    'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>86, 'student_id'=>37, 'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-03-04','note'=>'','month'=>'March',    'fee_details'=>'[{"fee_name":"Monthly Fee (3 Shift)","amount":10000}]'],
        ['id'=>87, 'student_id'=>35, 'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-03-04','note'=>'','month'=>'March',    'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":5000},{"fee_name":"Hifz","amount":1000}]'],
        ['id'=>88, 'student_id'=>24, 'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-03-04','note'=>'','month'=>'March',    'fee_details'=>'[{"fee_name":"Monthly Fee (3 Shift)","amount":10000}]'],
        ['id'=>89, 'student_id'=>5,  'payment_mode'=>'cash',          'payment_date'=>'2026-03-05','note'=>'','month'=>'March',    'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":5000}]'],
        ['id'=>90, 'student_id'=>3,  'payment_mode'=>'cash',          'payment_date'=>'2026-03-05','note'=>'','month'=>'March',    'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":8000}]'],
        ['id'=>91, 'student_id'=>36, 'payment_mode'=>'cash',          'payment_date'=>'2026-03-05','note'=>'','month'=>'March',    'fee_details'=>'[{"fee_name":"Monthly Fee (1 Shift)","amount":4000}]'],
        ['id'=>92, 'student_id'=>31, 'payment_mode'=>'cash',          'payment_date'=>'2026-03-05','note'=>'','month'=>'March',    'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":8000},{"fee_name":"Hifz","amount":2000}]'],
        ['id'=>93, 'student_id'=>8,  'payment_mode'=>'bank_transfer', 'payment_date'=>'2026-03-05','note'=>'','month'=>'March',    'fee_details'=>'[{"fee_name":"Monthly Tuition Fee","amount":8000}]'],
    ];

    // ─────────────────────────────────────────────────────────────────────
    // Internal maps built during migration
    // ─────────────────────────────────────────────────────────────────────
    private array $classMap   = [];  // old classroom.id  → new classes.id
    private array $sectionMap = [];  // "oldClassId:Name" → new section.id
    private array $studentMap = [];  // old student.id    → new student.id
    private array $feeMap     = [];  // "newClassId:feeName" → new fee_structure.id

    // ─────────────────────────────────────────────────────────────────────
    // FIX 1: "One Time" added — this was missing in the original seeder
    // causing Admission Fee frequency to silently default to 'one_time'
    // only by fallback. Now explicit.
    // ─────────────────────────────────────────────────────────────────────
    private array $FREQ_MAP = [
        'One Time'    => 'one_time',    // ← NEW — used by new selected_fees format
        'Monthly'     => 'monthly',
        'Quarterly'   => 'quarterly',
        'Half-Yearly' => 'half_yearly',
        'Half Yearly' => 'half_yearly', // ← extra alias just in case
        'Yearly'      => 'yearly',
    ];

    private array $ROLE_MAP = [
        'admin'            => 'Admin',
        'admission_office' => 'Admission Office',
    ];

    // ─────────────────────────────────────────────────────────────────────
    // FIX 2: payment_mode normalisation
    // Old data uses 'cash' / 'bank_transfer' (already lowercase snake_case)
    // New payments table enum: ['cash','bank_transfer','online','cheque','card']
    // The map below also handles any future mixed-case values from the new CSV.
    // ─────────────────────────────────────────────────────────────────────
    private array $PAYMENT_METHOD_MAP = [
        'cash'          => 'cash',
        'Cash'          => 'cash',
        'bank_transfer' => 'bank_transfer',
        'Bank Transfer' => 'bank_transfer',
        'bank transfer' => 'bank_transfer',
        'online'        => 'online',
        'Online'        => 'online',
        'cheque'        => 'cheque',
        'Cheque'        => 'cheque',
        'card'          => 'card',
        'Card'          => 'card',
    ];

    // ─────────────────────────────────────────────────────────────────────

    public function run(): void
    {
        $this->command->info('Starting old-project data migration…');

        DB::transaction(function () {
            $this->step1_migrateUsers();
            $this->step2_migrateClasses();
            $this->step3_migrateSections();
            $this->step4_migrateFeeStructures();
            $this->step5_migrateStudents();
            $this->step6_migrateStudentFeeAssignments();
            $this->step7_migratePayments();
        });

        $this->command->info('Migration complete!');
    }

    // ─────────────────────────────────────────────────────────────────────
    // STEP 1 — Users
    // ─────────────────────────────────────────────────────────────────────
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

            $newRole = $this->ROLE_MAP[$old['role']] ?? null;
            if ($newRole) {
                \Spatie\Permission\Models\Role::firstOrCreate(
                    ['name' => $newRole, 'guard_name' => 'web']
                );
                $user->syncRoles([$newRole]);
            }
        }

        $this->command->info('  → ' . count($this->OLD_USERS) . ' users migrated.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // STEP 2 — Classrooms → Classes
    // ─────────────────────────────────────────────────────────────────────
    private function step2_migrateClasses(): void
    {
        $this->command->info('[2/7] Migrating classrooms → classes…');

        foreach ($this->OLD_CLASSROOMS as $old) {
            $class = Classes::updateOrCreate(
                ['code' => strtoupper($old['class_id'])],
                [
                    'name'          => $old['name'],
                    'capacity'      => $old['max_students_per_section'] ?? 40,
                    'academic_year' => date('Y'),
                    'is_active'     => true,
                ]
            );

            $this->classMap[$old['id']] = $class->id;
        }

        $this->command->info('  → ' . count($this->OLD_CLASSROOMS) . ' classes migrated.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // STEP 3 — Sections
    // ─────────────────────────────────────────────────────────────────────
    private function step3_migrateSections(): void
    {
        $this->command->info('[3/7] Migrating sections…');

        $count = 0;
        foreach ($this->OLD_CLASSROOMS as $old) {
            $newClassId = $this->classMap[$old['id']] ?? null;
            if (! $newClassId) continue;

            $sectionNames = json_decode($old['sections'] ?? '[]', true) ?? [];

            foreach ($sectionNames as $sectionName) {
                $section = Section::firstOrCreate(
                    ['class_id' => $newClassId, 'name' => $sectionName],
                    ['capacity' => $old['max_students_per_section'] ?? 40]
                );

                $this->sectionMap[$old['id'] . ':' . $sectionName] = $section->id;
                $count++;
            }
        }

        $this->command->info("  → {$count} sections migrated.");
    }

    // ─────────────────────────────────────────────────────────────────────
    // STEP 4 — FeeStructures
    // Reads fees from the old classroom JSON array + the admission_fee field.
    // This step is unchanged from the original — the classroom data format
    // is the same in old and new exports.
    // ─────────────────────────────────────────────────────────────────────
    private function step4_migrateFeeStructures(): void
    {
        $this->command->info('[4/7] Migrating fee structures…');

        $count = 0;
        foreach ($this->OLD_CLASSROOMS as $old) {
            $newClassId = $this->classMap[$old['id']] ?? null;
            if (! $newClassId) continue;

            // Regular fees (Monthly, Half-Yearly, etc.)
            $fees = json_decode($old['fees'] ?? '[]', true) ?? [];
            foreach ($fees as $fee) {
                $frequency = $this->FREQ_MAP[$fee['type'] ?? ''] ?? 'one_time';

                $fs = FeeStructure::firstOrCreate(
                    [
                        'class_id'  => $newClassId,
                        'fee_type'  => $fee['name'],
                        'frequency' => $frequency,
                    ],
                    [
                        'amount'        => $fee['amount'],
                        'academic_year' => date('Y'),
                        'is_mandatory'  => true,
                    ]
                );

                $this->feeMap[$newClassId . ':' . $fee['name']] = $fs->id;
                $count++;
            }

            // Admission fee (separate field on old classroom)
            if (! empty($old['admission_fee'])) {
                $fs = FeeStructure::firstOrCreate(
                    [
                        'class_id'  => $newClassId,
                        'fee_type'  => 'Admission Fee',
                        'frequency' => 'one_time',
                    ],
                    [
                        'amount'        => $old['admission_fee'],
                        'academic_year' => date('Y'),
                        'is_mandatory'  => true,
                    ]
                );

                $this->feeMap[$newClassId . ':Admission Fee'] = $fs->id;
                $count++;
            }
        }

        $this->command->info("  → {$count} fee structures migrated.");
    }

    // ─────────────────────────────────────────────────────────────────────
    // STEP 5 — Students
    // ─────────────────────────────────────────────────────────────────────
    private function step5_migrateStudents(): void
    {
        $this->command->info('[5/7] Migrating students…');

        foreach ($this->OLD_STUDENTS as $old) {
            $nameParts = explode(' ', trim($old['name']), 2);
            $firstName = $nameParts[0];
            $lastName  = $nameParts[1] ?? '';

            $newSectionId = $this->sectionMap[$old['class_id'] . ':' . ($old['section'] ?? '')] ?? null;
            $newClassId   = $this->classMap[$old['class_id']] ?? null;

            $student = Student::updateOrCreate(
                ['student_id' => $old['student_id']],
                [
                    'first_name'     => $firstName,
                    'last_name'      => $lastName,
                    'date_of_birth'  => ! empty($old['dob']) ? $old['dob'] : '2010-01-01',
                    'gender'         => strtolower($old['gender'] ?? 'male'),
                    'email'          => ! empty($old['email']) ? $old['email'] : null,
                    'phone'          => ! empty($old['father_mobile']) ? $old['father_mobile'] : '00000000000',
                    'address'        => $old['address'] ?? null,
                    'guardian_name'  => $old['father_name'] ?? '',
                    'guardian_phone' => $old['father_mobile'] ?? '',
                    'class_id'       => $newClassId,
                    'section_id'     => $newSectionId,
                    'enrollment_date'=> $old['enrollment_date'] ?? now()->toDateString(),
                    'status'         => $old['status'] ?? 'active',
                ]
            );

            $this->studentMap[$old['id']] = $student->id;
        }

        $this->command->info('  → ' . count($this->OLD_STUDENTS) . ' students migrated.');
    }

    // ─────────────────────────────────────────────────────────────────────
    // STEP 6 — StudentFeeAssignments
    //
    // FIX A: selected_fees key changed from "fee_name" (old) → "name" (new)
    //        Handled with:  $sel['fee_name'] ?? $sel['name'] ?? ''
    //
    // FIX B: discounts field changed format between old and new:
    //   OLD (array):   [{"fee_name":"X","discount_type":"fixed","value":Y}]
    //   NEW (object):  {"X":{"amount":Y,"permanent":0|1}}
    //        Handled by detecting array_is_list() and normalising to a
    //        common structure before processing.
    // ─────────────────────────────────────────────────────────────────────
    private function step6_migrateStudentFeeAssignments(): void
    {
        $this->command->info('[6/7] Migrating student fee assignments…');

        $count = 0;
        foreach ($this->OLD_STUDENTS as $old) {
            $newStudentId = $this->studentMap[$old['id']] ?? null;
            $newClassId   = $this->classMap[$old['class_id']] ?? null;
            if (! $newStudentId || ! $newClassId) continue;

            $selectedFees = json_decode($old['selected_fees'] ?? '[]', true) ?? [];
            $rawDiscounts = json_decode($old['discounts']     ?? '[]', true) ?? [];

            // ── Normalise discount lookup keyed by fee name ──────────────
            // Supports BOTH old array format AND new keyed-object format.
            $discountMap = [];

            if (is_array($rawDiscounts) && ! empty($rawDiscounts)) {
                if (array_is_list($rawDiscounts)) {
                    // OLD format: [{fee_name, discount_type, value, is_permanent?}]
                    foreach ($rawDiscounts as $d) {
                        $key = $d['fee_name'] ?? '';
                        if ($key === '') continue;
                        $discountMap[$key] = [
                            'discount_type' => $d['discount_type'] ?? 'fixed',
                            'value'         => (float) ($d['value'] ?? 0),
                            'is_permanent'  => (bool) ($d['is_permanent'] ?? false),
                        ];
                    }
                } else {
                    // NEW format: {"FeeName": {"amount": X, "permanent": 0|1}}
                    foreach ($rawDiscounts as $feeName => $d) {
                        $discountMap[$feeName] = [
                            'discount_type' => 'fixed',
                            'value'         => (float) ($d['amount'] ?? 0),
                            'is_permanent'  => (bool) ($d['permanent'] ?? false),
                        ];
                    }
                }
            }
            // ────────────────────────────────────────────────────────────

            foreach ($selectedFees as $sel) {
                // FIX A: support both "fee_name" (old) and "name" (new)
                $feeName  = $sel['fee_name'] ?? $sel['name'] ?? '';
                $feeKey   = $newClassId . ':' . $feeName;
                $newFeeId = $this->feeMap[$feeKey] ?? null;

                if (! $newFeeId) {
                    $this->command->warn("    ⚠ FeeStructure not found for: [{$feeKey}] — skipping assignment.");
                    continue;
                }

                // Prefer structured discount from $discountMap,
                // fall back to inline discount value from selected_fees
                $disc          = $discountMap[$feeName] ?? [];
                $inlineDisc    = (float) ($sel['discount'] ?? 0);
                $discountType  = match ($disc['discount_type'] ?? 'none') {
                    'percentage' => 'percentage',
                    'fixed'      => 'fixed',
                    default      => $inlineDisc > 0 ? 'fixed' : 'none',
                };
                $discountValue = (float) ($disc['value'] ?? $inlineDisc);
                $isPermanent   = (bool)  ($disc['is_permanent'] ?? $sel['is_permanent'] ?? false);

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

        $this->command->info("  → {$count} fee assignments migrated.");
    }

    // ─────────────────────────────────────────────────────────────────────
    // STEP 7 — Payments
    //
    // FIX A: fee_details items use "fee_name" key in old data.
    //        New data uses "name".  Handled with null-coalescing.
    //
    // FIX B: New payments table has NO billing_month / billing_year columns.
    //        Those fields are removed from the insert.
    //
    // FIX C: Monthly fee names in new data carry a month suffix:
    //        "Monthly Tuition Fee - January, 26"
    //        We strip the suffix before writing fee_type so the value
    //        matches the FeeStructure fee_type exactly.
    //
    // FIX D: payment_method normalised through $PAYMENT_METHOD_MAP.
    // ─────────────────────────────────────────────────────────────────────
    private function step7_migratePayments(): void
    {
        $this->command->info('[7/7] Migrating payments…');

        $count = 0;
        foreach ($this->OLD_PAYMENTS as $old) {
            $newStudentId = $this->studentMap[$old['student_id']] ?? null;
            if (! $newStudentId) continue;

            // Deterministic receipt number — safe for re-runs
            $receiptBase   = 'MIG-' . str_pad($old['id'], 5, '0', STR_PAD_LEFT);
            $paymentMethod = $this->PAYMENT_METHOD_MAP[$old['payment_mode'] ?? ''] ?? 'cash';
            $paymentDate   = $old['payment_date'] ?? now()->toDateString();

            $feeDetails = json_decode($old['fee_details'] ?? '[]', true) ?? [];
            if (empty($feeDetails)) {
                $feeDetails = [['fee_name' => 'Fee', 'amount' => $old['amount'] ?? 0]];
            }

            foreach ($feeDetails as $idx => $item) {
                // FIX A: support both "fee_name" (old) and "name" (new)
                $rawName = $item['fee_name'] ?? $item['name'] ?? 'Fee';

                // FIX C: strip " - Month, YY" suffix from monthly fee names
                // e.g. "Monthly Tuition Fee - January, 26" → "Monthly Tuition Fee"
                $feeType = preg_replace('/\s*-\s*[A-Za-z]+,?\s*\d{2,4}$/', '', $rawName);
                $feeType = trim($feeType);

                // Make receipt unique per fee line within the same payment
                $receiptNumber = $receiptBase . ($idx > 0 ? '-' . ($idx + 1) : '');

                Payment::firstOrCreate(
                    [
                        'receipt_number' => $receiptNumber,
                        'fee_type'       => $feeType,
                    ],
                    [
                        'student_id'     => $newStudentId,
                        'amount'         => $item['amount'] ?? 0,
                        'payment_date'   => $paymentDate,
                        'payment_method' => $paymentMethod,
                        'remarks'        => $old['note'] ?: 'Migrated from old system',
                        'status'         => 'completed',
                        'received_by'    => 1,
                    ]
                );

                $count++;
            }
        }

        $this->command->info("  → {$count} payment rows migrated.");
    }
}