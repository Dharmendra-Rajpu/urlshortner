<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;


class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Company::create([
            'name' => 'ASP Corp',
            'email' => 'aspcorp@example.com',
        ]);

        Company::create([
            'name' => 'Sembark Tech',
            'email' => 'sembark@example.com',
        ]);
    }
}
