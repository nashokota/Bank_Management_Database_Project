<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Customer::create([
            'name' => 'John Smith',
            'dob' => '1985-06-15',
            'address' => '123 Maple Street, Springfield, IL 62701',
            'phone' => '+1-555-1001',
            'email' => 'john.smith@email.com'
        ]);

        \App\Models\Customer::create([
            'name' => 'Sarah Johnson',
            'dob' => '1990-03-22',
            'address' => '456 Oak Avenue, Springfield, IL 62702',
            'phone' => '+1-555-1002',
            'email' => 'sarah.johnson@email.com'
        ]);

        \App\Models\Customer::create([
            'name' => 'Michael Brown',
            'dob' => '1988-11-08',
            'address' => '789 Pine Road, Springfield, IL 62703',
            'phone' => '+1-555-1003',
            'email' => 'michael.brown@email.com'
        ]);

        \App\Models\Customer::create([
            'name' => 'Emily Davis',
            'dob' => '1992-09-14',
            'address' => '321 Cedar Lane, Springfield, IL 62704',
            'phone' => '+1-555-1004',
            'email' => 'emily.davis@email.com'
        ]);

        \App\Models\Customer::create([
            'name' => 'David Wilson',
            'dob' => '1987-01-30',
            'address' => '654 Elm Street, Springfield, IL 62705',
            'phone' => '+1-555-1005',
            'email' => 'david.wilson@email.com'
        ]);

        \App\Models\Customer::create([
            'name' => 'Lisa Anderson',
            'dob' => '1991-07-12',
            'address' => '987 Birch Drive, Springfield, IL 62706',
            'phone' => '+1-555-1006',
            'email' => 'lisa.anderson@email.com'
        ]);

        \App\Models\Customer::create([
            'name' => 'Robert Martinez',
            'dob' => '1989-04-25',
            'address' => '147 Walnut Court, Springfield, IL 62707',
            'phone' => '+1-555-1007',
            'email' => 'robert.martinez@email.com'
        ]);

        \App\Models\Customer::create([
            'name' => 'Jennifer Taylor',
            'dob' => '1993-12-03',
            'address' => '258 Spruce Place, Springfield, IL 62708',
            'phone' => '+1-555-1008',
            'email' => 'jennifer.taylor@email.com'
        ]);
    }
}
