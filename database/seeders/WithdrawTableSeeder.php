<?php

namespace Database\Seeders;

use App\Models\Withdraw;
use Illuminate\Database\Seeder;

class WithdrawTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Withdraw::factory()->count(1000)->create();
    }
}
