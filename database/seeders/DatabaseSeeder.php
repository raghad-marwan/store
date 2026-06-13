<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,   // الأدمن
            ProductSeeder::class,     // المنتجات
            OrderSeeder::class,       // الطلبات
            ReviewSeeder::class,      // التقييمات
        ]);
    }
}
