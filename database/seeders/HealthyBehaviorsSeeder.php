<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HealthyBehaviorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('healthy_behaviors')->insert([
            ['behavior' => 'Smoking'],
            ['behavior' => 'Alcohol consumption'],
            ['behavior' => 'Physical activity'],
            ['behavior' => 'Balanced diet'],
            ['behavior' => 'Sleep hygiene'],
            ['behavior' => 'Excessive screen time'],
            ['behavior' => 'Hydration'],
            ['behavior' => 'Stress management'],
            ['behavior' => 'Drug use'],
            ['behavior' => 'Sun exposure']
        ]);
    }
}