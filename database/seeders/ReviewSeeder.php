<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $products = Product::all();

        $reviewsData = [
            ['user_name' => 'باسم', 'product_id' => 1, 'rating' => 4, 'comment' => 'رؤية ممتازة وسرعة في التوصيل', 'date' => '2025-02-15'],
            ['user_name' => 'أحمد', 'product_id' => 3, 'rating' => 5, 'comment' => 'رائع جدا .. الجهاز أصلي والتغليف محترم', 'date' => '2025-02-14'],
            ['user_name' => 'ندى', 'product_id' => 4, 'rating' => 4, 'comment' => 'عن تجربة الجهاز ممتاز وبيشتغل بكفاءة', 'date' => '2025-02-13'],
            ['user_name' => 'أيهم', 'product_id' => 5, 'rating' => 4, 'comment' => 'good brand - منتج أصلي وسريع', 'date' => '2025-02-12'],
            ['user_name' => 'ليلى', 'product_id' => 7, 'rating' => 5, 'comment' => 'السماعات رووعة والصوت نقي جدا', 'date' => '2025-02-11'],
            ['user_name' => 'محمود', 'product_id' => 2, 'rating' => 3, 'comment' => 'الجوال حلو بس البطارية مش قوية', 'date' => '2025-02-10'],
            ['user_name' => 'فاطمة', 'product_id' => 6, 'rating' => 5, 'comment' => 'التابلت كبير وسريع ومناسب للدراسة', 'date' => '2025-02-09'],
            ['user_name' => 'عمر', 'product_id' => 8, 'rating' => 5, 'comment' => 'اللابتوب خفيف وسريع والبطارية ممتازة', 'date' => '2025-02-08'],
        ];

        foreach ($reviewsData as $data) {
            $user = $users->firstWhere('name', $data['user_name']);

            if (!$user) {
                $user = User::factory()->create([
                    'name' => $data['user_name'],
                    'role' => 'user',
                ]);
            }

            Review::create([
                'user_id' => $user->id,
                'product_id' => $data['product_id'],
                'rating' => $data['rating'],
                'comment' => $data['comment'],
                'created_at' => $data['date'],
                'updated_at' => $data['date'],
            ]);
        }

        $this->command->info('✅ تم إضافة ' . count($reviewsData) . ' تقييمات بنجاح');
    }
}
