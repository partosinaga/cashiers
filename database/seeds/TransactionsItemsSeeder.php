<?php

use Illuminate\Database\Seeder;

class TransactionsItemsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transaction_items')->insert([
            'uuid' => Str::uuid(),
            'transaction_id' => 3,
            'title' => 'laron',
            'qty' => 1,
            'price' => 200000,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
