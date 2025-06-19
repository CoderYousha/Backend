<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicinesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('medicines')->insert([
            ['medicine_name' => 'Paracetamol'],
            ['medicine_name' => 'Ibuprofen'],
            ['medicine_name' => 'Metformin'],
            ['medicine_name' => 'Aspirin'],
            ['medicine_name' => 'Amoxicillin'],
            ['medicine_name' => 'Omeprazole'],
            ['medicine_name' => 'Atorvastatin'],
            ['medicine_name' => 'Ciprofloxacin'],
            ['medicine_name' => 'Losartan'],
            ['medicine_name' => 'Salbutamol']
        ]);
    }
}