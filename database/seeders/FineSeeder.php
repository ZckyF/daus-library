<?php

namespace Database\Seeders;

use App\Models\Fine;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fine::factory()->create([
        //     'fine_code' => 'FC998',
        //     'member_id' => "",
        //     'non_member_name' => 'Ahmad Muhammad',
        //     'amount' => 100000,
        //     'amount_paid' => 200000,
        //     'change_amount' => 100000,
        //     'reason' => 'Bayar denda untuk rak. hancur parah',
        //     'charged_at' => now(),
        //     'is_paid' => false,
        //     'user_id' => 3,
        // ]);
        Fine::factory(10)->create();
    }
}
