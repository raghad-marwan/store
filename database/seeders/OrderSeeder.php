<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $customers = User::where('role', 'user')->get();

        // إذا ما في مستخدمين، ننشئ مستخدمين
        if ($customers->isEmpty()) {
            User::factory(5)->create(['role' => 'user']);
            $customers = User::where('role', 'user')->get();
        }

        $products = Product::all();

        $ordersData = [
            [
                'customer_name' => 'محمد علي',
                'phone' => '0599123456',
                'address' => 'غزة - الرمال',
                'status' => 'pending',
                'items' => [
                    ['product_id' => 1, 'quantity' => 1],
                ],
            ],
            [
                'customer_name' => 'سارة أحمد',
                'phone' => '0599234567',
                'address' => 'القدس - بيت حنينا',
                'status' => 'processing',
                'items' => [
                    ['product_id' => 3, 'quantity' => 1],
                ],
            ],
            [
                'customer_name' => 'خالد يوسف',
                'phone' => '0599345678',
                'address' => 'رام الله - المصيون',
                'status' => 'pending',
                'items' => [
                    ['product_id' => 2, 'quantity' => 1],
                ],
            ],
            [
                'customer_name' => 'أمل حسن',
                'phone' => '0599456789',
                'address' => 'نابلس - رفيديا',
                'status' => 'shipped',
                'items' => [
                    ['product_id' => 4, 'quantity' => 2],
                    ['product_id' => 7, 'quantity' => 1],
                ],
            ],
            [
                'customer_name' => 'رامي عبدالله',
                'phone' => '0599567890',
                'address' => 'الخليل - الحاووز',
                'status' => 'delivered',
                'items' => [
                    ['product_id' => 5, 'quantity' => 1],
                ],
            ],
        ];

        foreach ($ordersData as $data) {
            $user = $customers->random();

            $total = 0;
            $itemsData = [];

            foreach ($data['items'] as $item) {
                $product = $products->find($item['product_id']);
                if ($product) {
                    $subtotal = $product->price * $item['quantity'];
                    $total += $subtotal;
                    $itemsData[] = [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ];
                }
            }

            $order = Order::create([
                'user_id' => $user->id,
                'total' => $total,
                'status' => $data['status'],
                'customer_name' => $data['customer_name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
            ]);

            foreach ($itemsData as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);
            }
        }

        $this->command->info('✅ تم إضافة ' . count($ordersData) . ' طلبات بنجاح');
    }
}
