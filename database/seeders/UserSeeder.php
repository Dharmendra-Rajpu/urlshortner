<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Company;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement("
            INSERT INTO users (name, email, password, role, company_id, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, NOW(), NOW())
        ", [
            'Super Admin',
            'superadmin@example.com',
            Hash::make('password'),
            'SuperAdmin',
            null,
        ]);

        $companyOne = Company::where('email', 'aspcorp@example.com')->first();
        $companyTwo = Company::where('email', 'sembark@example.com')->first();

        // ASP Corp users
        User::create([
            'name' => 'Admin One',
            'email' => 'admin1@example.com',
            'password' => Hash::make('password'),
            'role' => 'Admin',
            'company_id' => $companyOne->id,
        ]);

        User::create([
            'name' => 'Member One',
            'email' => 'member1@example.com',
            'password' => Hash::make('password'),
            'role' => 'Member',
            'company_id' => $companyOne->id,
        ]);

        User::create([
            'name' => 'Sales One',
            'email' => 'sales1@example.com',
            'password' => Hash::make('password'),
            'role' => 'Sales',
            'company_id' => $companyOne->id,
        ]);

        // Sembark Tech users
        User::create([
            'name' => 'Admin Two',
            'email' => 'admin2@example.com',
            'password' => Hash::make('password'),
            'role' => 'Admin',
            'company_id' => $companyTwo->id,
        ]);

        User::create([
            'name' => 'Manager Two',
            'email' => 'manager2@example.com',
            'password' => Hash::make('password'),
            'role' => 'Manager',
            'company_id' => $companyTwo->id,
        ]);
    }
}
