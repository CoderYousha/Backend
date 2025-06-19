<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IllsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('illneses')->insert([
            ['ill_name' => 'Diabetes'],
            ['ill_name' => 'Asthma'],
            ['ill_name' => 'Hypertension'],
            ['ill_name' => 'Alzheimer\'s disease'],
            ['ill_name' => 'Cancer'],
            ['ill_name' => 'Hepatitis'],
            ['ill_name' => 'Multiple Sclerosis'],
            ['ill_name' => 'Epilepsy'],
            ['ill_name' => 'Influenza'],
            ['ill_name' => 'Kidney Failure']
        ]);
    }
}