<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Savings Accounts
        \App\Models\Account::create([
            'customer_id' => 1,
            'branch_id' => 1,
            'account_type' => 'savings',
            'balance' => 5000.00,
            'date_opened' => '2024-01-15',
            'status' => 'active'
        ]);

        \App\Models\Account::create([
            'customer_id' => 2,
            'branch_id' => 2,
            'account_type' => 'savings',
            'balance' => 7500.00,
            'date_opened' => '2024-02-10',
            'status' => 'active'
        ]);

        \App\Models\Account::create([
            'customer_id' => 3,
            'branch_id' => 1,
            'account_type' => 'current',
            'balance' => 12000.00,
            'date_opened' => '2024-01-20',
            'status' => 'active'
        ]);

        // Current Accounts
        \App\Models\Account::create([
            'customer_id' => 4,
            'branch_id' => 3,
            'account_type' => 'current',
            'balance' => 25000.00,
            'date_opened' => '2023-12-05',
            'status' => 'active'
        ]);

        \App\Models\Account::create([
            'customer_id' => 5,
            'branch_id' => 2,
            'account_type' => 'savings',
            'balance' => 3200.00,
            'date_opened' => '2024-03-01',
            'status' => 'active'
        ]);

        // Fixed Deposits
        \App\Models\Account::create([
            'customer_id' => 6,
            'branch_id' => 4,
            'account_type' => 'fixed_deposit',
            'balance' => 50000.00,
            'date_opened' => '2023-11-15',
            'status' => 'active'
        ]);

        \App\Models\Account::create([
            'customer_id' => 7,
            'branch_id' => 5,
            'account_type' => 'savings',
            'balance' => 8900.00,
            'date_opened' => '2024-02-20',
            'status' => 'active'
        ]);

        \App\Models\Account::create([
            'customer_id' => 8,
            'branch_id' => 1,
            'account_type' => 'current',
            'balance' => 15600.00,
            'date_opened' => '2024-01-08',
            'status' => 'active'
        ]);

        // Some accounts for the same customers in different branches
        \App\Models\Account::create([
            'customer_id' => 1,
            'branch_id' => 3,
            'account_type' => 'fixed_deposit',
            'balance' => 100000.00,
            'date_opened' => '2023-10-01',
            'status' => 'active'
        ]);

        \App\Models\Account::create([
            'customer_id' => 2,
            'branch_id' => 4,
            'account_type' => 'current',
            'balance' => 18500.00,
            'date_opened' => '2024-01-25',
            'status' => 'active'
        ]);

        // Closed account example
        \App\Models\Account::create([
            'customer_id' => 3,
            'branch_id' => 5,
            'account_type' => 'savings',
            'balance' => 0.00,
            'date_opened' => '2023-08-15',
            'status' => 'closed'
        ]);
    }
}
