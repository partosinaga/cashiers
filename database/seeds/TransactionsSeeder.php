<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TransactionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transactions')->insert([
            'uuid' => Str::uuid(),
            'user_id' => 1,
            'device_timestamp' => date('Y-m-d H:i:s'),
            'total_amount' => 900000,
            'paid_amount' => 1000000,
            'change_amount' => 100000,
            'payment_method' => 'cash',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
