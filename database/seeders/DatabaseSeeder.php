<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Clinic;
use App\Models\DoctorClinic;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /* Admin */
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456789'),
            'phone_number' => '0977413221',
            'account_type' => 'admin',
            'birth_date' => '12-12-1990',
            'address' => 'Damascus - Mazah',
        ]);

        /* Reception */
        User::create([
            'name' => 'Ahmad',
            'username' => 'ahmad',
            'email' => 'ahmad@gmail.com',
            'password' => Hash::make('123456789'),
            'phone_number' => '0944121544',
            'account_type' => 'reception',
            'birth_date' => '12-01-1995',
            'address' => 'Damascus - Mazah',
        ]);

        /* Doctor */
        $doctor = User::create([
            'name' => 'Yzen',
            'username' => 'yzenkhalil',
            'email' => 'yzen@gmail.com',
            'password' => Hash::make('123456789'),
            'phone_number' => '0944713655',
            'account_type' => 'doctor',
            'birth_date' => '12-01-1980',
            'address' => 'Damascus - Mazah',
            'medical_specialization' => 'Heart',
        ]);

        /* Advertisement */
        $advertisement = User::create([
            'name' => 'Ali',
            'username' => 'alisaleh',
            'email' => 'ali@gmail.com',
            'password' => Hash::make('123456789'),
            'phone_number' => '0947123789',
            'account_type' => 'advertisement',
            'birth_date' => '12-01-1980',
            'address' => 'Damascus - Mazah',
        ]);

        /* Technical Doctor */
        $doctor = User::create([
            'name' => 'Hasan',
            'username' => 'hasankhatib',
            'email' => 'hasan@gmail.com',
            'password' => Hash::make('123456789'),
            'phone_number' => '0944122314',
            'account_type' => 'doctor',
            'birth_date' => '12-01-1990',
            'address' => 'Damascus - Mazah',
            'medical_specialization' => 'Technical',
        ]);

        /* Clinic */
        $clinic = Clinic::create([
            'clinic_name' => 'Heart Clinic',
            'floor_number' => 1,
            'image' => "url_image_here",
        ]);

        /* Clinic - Doctor */
        DoctorClinic::create([
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor->id,
        ]);
    }
}