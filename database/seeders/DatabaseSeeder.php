<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Merchant;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(50)->create();

        Merchant::factory(20)->create();

        Product::factory(40)->create();

         Order::factory(200)->create()->each(function ($order) {
            $order->products()->attach(rand(1, 20), [
                'quantity' => rand(1, 100)
            ]);
        });

    }
}
