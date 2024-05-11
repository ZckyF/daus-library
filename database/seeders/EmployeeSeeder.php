<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Employee::factory()->createMany(
            [
                [
                    'full_name' => 'John Lawson',
                    'email' => 'johnlawson333@example.com',
                    'number_phone' => '123-456-7770',
                    'address' => '123 Main St',
                    'nik' => '1234567'
                ],
                [
                    'full_name' => 'Johnny',
                    'email' => 'johny8877@example.com',
                    'number_phone' => '123-456-7880',
                    'address' => '124 Main St',
                    'nik' => '1233567'
                ],
                [
                    'full_name' => 'Jamal',
                    'email' => 'jamal22@example.com',
                    'number_phone' => '123-456-9990',
                    'address' => '125 Main St',
                    'nik' => '1235567'
                ],
                [
                    'full_name' => 'Rio',
                    'email' => 'rio33@example.com',
                    'number_phone' => '123-456-8888',
                    'address' => '126 Main St',
                    'nik' => '1224567'
                ] 
            ]
        );
        Employee::factory(10)->create();
    }
}
