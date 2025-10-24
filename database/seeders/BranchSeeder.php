<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Branch::create([
            'branch_name' => 'Main Branch',
            'address' => '123 Downtown Street, Financial District, City Center',
            'phone' => '+1-555-0101'
        ]);

        \App\Models\Branch::create([
            'branch_name' => 'North Branch',
            'address' => '456 North Avenue, Residential Area, Uptown',
            'phone' => '+1-555-0102'
        ]);

        \App\Models\Branch::create([
            'branch_name' => 'South Branch',
            'address' => '789 South Boulevard, Commercial District, Southside',
            'phone' => '+1-555-0103'
        ]);

        \App\Models\Branch::create([
            'branch_name' => 'East Branch',
            'address' => '321 East Road, Business Park, Eastside',
            'phone' => '+1-555-0104'
        ]);

        \App\Models\Branch::create([
            'branch_name' => 'West Branch',
            'address' => '654 West Street, Shopping Mall, Westside',
            'phone' => '+1-555-0105'
        ]);
    }
}
