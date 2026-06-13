<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // ========================================
            // 📱 جوالات - Smartphones (10 منتجات)
            // ========================================
            [
                'name' => 'iPhone 15 Pro Max',
                'description' => 'أحدث جوالات آبل مع شريحة A17 Pro وتصميم تيتانيوم',
                'price' => 1499.00, 'stock' => 10, 'category' => 'smartphones', 'brand' => 'apple',
                'is_active' => true, 'sales_count' => 38,
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'description' => 'جوال سامسونج الرائد بشاشة Dynamic AMOLED وقلم S Pen',
                'price' => 1299.00, 'stock' => 15, 'category' => 'smartphones', 'brand' => 'samsung',
                'is_active' => true, 'sales_count' => 45,
            ],
            [
                'name' => 'Google Pixel 8 Pro',
                'description' => 'جوال جوجل بكاميرا احترافية ومعالج Tensor G3',
                'price' => 899.00, 'stock' => 12, 'category' => 'smartphones', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 20,
            ],
            [
                'name' => 'OnePlus 12',
                'description' => 'جوال ون بلس بمعالج Snapdragon 8 Gen 3',
                'price' => 799.00, 'stock' => 18, 'category' => 'smartphones', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 18,
            ],
            [
                'name' => 'Xiaomi 14 Ultra',
                'description' => 'جوال شاومي الرائد بكاميرا Leica الاحترافية',
                'price' => 1099.00, 'stock' => 14, 'category' => 'smartphones', 'brand' => 'xiaomi',
                'is_active' => true, 'sales_count' => 30,
            ],
            [
                'name' => 'iPhone 15',
                'description' => 'جوال آبل بشريحة A16 وديناميك آيلاند',
                'price' => 899.00, 'stock' => 20, 'category' => 'smartphones', 'brand' => 'apple',
                'is_active' => true, 'sales_count' => 55,
            ],
            [
                'name' => 'Samsung Galaxy Z Fold 5',
                'description' => 'جوال سامسونج القابل للطي بشاشة كبيرة',
                'price' => 1799.00, 'stock' => 8, 'category' => 'smartphones', 'brand' => 'samsung',
                'is_active' => true, 'sales_count' => 22,
            ],
            [
                'name' => 'Redmi Note 13 Pro',
                'description' => 'جوال شاومي اقتصادي بمواصفات ممتازة',
                'price' => 349.00, 'stock' => 40, 'category' => 'smartphones', 'brand' => 'xiaomi',
                'is_active' => true, 'sales_count' => 70,
            ],
            [
                'name' => 'Tecno Phantom V Flip',
                'description' => 'جوال تكنو القابل للطي بتصميم أنيق',
                'price' => 699.00, 'stock' => 25, 'category' => 'smartphones', 'brand' => 'tecno',
                'is_active' => true, 'sales_count' => 15,
            ],
            [
                'name' => 'Honor Magic 6 Pro',
                'description' => 'جوال هونر الرائد بكاميرا 180 ميجابكسل',
                'price' => 899.00, 'stock' => 11, 'category' => 'smartphones', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 12,
            ],

            // ========================================
            // 💻 لابتوب - Laptops (6 منتجات)
            // ========================================
            [
                'name' => 'MacBook Pro 16 M3 Max',
                'description' => 'لابتوب آبل الاحترافي للمصممين والمبرمجين',
                'price' => 3499.00, 'stock' => 5, 'category' => 'laptops', 'brand' => 'apple',
                'is_active' => true, 'sales_count' => 25,
            ],
            [
                'name' => 'Dell XPS 15',
                'description' => 'لابتوب ديل بشاشة OLED وإطار نحيف',
                'price' => 1899.00, 'stock' => 8, 'category' => 'laptops', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 32,
            ],
            [
                'name' => 'Lenovo ThinkPad X1 Carbon',
                'description' => 'لابتوب لينوفو الخفيف لرجال الأعمال',
                'price' => 1599.00, 'stock' => 10, 'category' => 'laptops', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 28,
            ],
            [
                'name' => 'HP Spectre x360',
                'description' => 'لابتوب HP متحول بشاشة لمس وقلم',
                'price' => 1399.00, 'stock' => 12, 'category' => 'laptops', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 20,
            ],
            [
                'name' => 'ASUS ROG Zephyrus G14',
                'description' => 'لابتوب ألعاب قوي بمعالج Ryzen 9',
                'price' => 1799.00, 'stock' => 6, 'category' => 'laptops', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 18,
            ],
            [
                'name' => 'Microsoft Surface Laptop 6',
                'description' => 'لابتوب مايكروسوفت الأنيق بشاشة PixelSense',
                'price' => 1299.00, 'stock' => 9, 'category' => 'laptops', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 15,
            ],

            // ========================================
            // 📟 أجهزة لوحية - Tablets (4 منتجات)
            // ========================================
            [
                'name' => 'iPad Pro M4 12.9',
                'description' => 'تابلت آبل الاحترافي بمعالج M4 الجديد',
                'price' => 1099.00, 'stock' => 10, 'category' => 'tablets', 'brand' => 'apple',
                'is_active' => true, 'sales_count' => 35,
            ],
            [
                'name' => 'Samsung Galaxy Tab S9 FE',
                'description' => 'تابلت سامسونج مع قلم S Pen للدراسة والعمل',
                'price' => 449.00, 'stock' => 15, 'category' => 'tablets', 'brand' => 'samsung',
                'is_active' => true, 'sales_count' => 25,
            ],
            [
                'name' => 'iPad Air M2',
                'description' => 'تابلت آبل بمعالج M2 وأداء قوي',
                'price' => 599.00, 'stock' => 20, 'category' => 'tablets', 'brand' => 'apple',
                'is_active' => true, 'sales_count' => 50,
            ],
            [
                'name' => 'Xiaomi Pad 6 Max',
                'description' => 'تابلت شاومي العملاق بشاشة 14 بوصة',
                'price' => 449.00, 'stock' => 15, 'category' => 'tablets', 'brand' => 'xiaomi',
                'is_active' => true, 'sales_count' => 20,
            ],

            // ========================================
            // 🏠 أجهزة منزلية ذكية - Smart Home (6 منتجات)
            // ========================================
            [
                'name' => 'iRobot Roomba j9+',
                'description' => 'مكنسة روبوت ذكية مع محطة تفريغ أوتوماتيكية',
                'price' => 899.00, 'stock' => 8, 'category' => 'smart-home', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 28,
            ],
            [
                'name' => 'Google Nest Hub Max',
                'description' => 'شاشة ذكية من جوجل مع كاميرا مدمجة',
                'price' => 229.00, 'stock' => 30, 'category' => 'smart-home', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 65,
            ],
            [
                'name' => 'Amazon Echo Show 10',
                'description' => 'شاشة ذكية دوارة مع Alexa',
                'price' => 249.00, 'stock' => 25, 'category' => 'smart-home', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 45,
            ],
            [
                'name' => 'Xiaomi Smart Air Purifier 4',
                'description' => 'منقي هواء ذكي من شاومي',
                'price' => 199.00, 'stock' => 20, 'category' => 'smart-home', 'brand' => 'xiaomi',
                'is_active' => true, 'sales_count' => 40,
            ],
            [
                'name' => 'Philips Hue Starter Kit',
                'description' => 'طقم إضاءة ذكية من فيليبس',
                'price' => 149.00, 'stock' => 35, 'category' => 'smart-home', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 55,
            ],
            [
                'name' => 'Ring Video Doorbell Pro 2',
                'description' => 'جرس باب ذكي مع كاميرا HD',
                'price' => 199.00, 'stock' => 22, 'category' => 'smart-home', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 35,
            ],

            // ========================================
            // 🎧 سماعات واكسسوارات - Accessories (8 منتجات)
            // ========================================
            [
                'name' => 'Sony WH-1000XM5',
                'description' => 'سماعات سوني لاسلكية مع أفضل عزل للضوضاء',
                'price' => 349.00, 'stock' => 20, 'category' => 'accessories', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 45,
            ],
            [
                'name' => 'Samsung Galaxy Buds3 Pro',
                'description' => 'سماعات سامسونج بصوت Hi-Fi وميكروفون محسن',
                'price' => 229.00, 'stock' => 35, 'category' => 'accessories', 'brand' => 'samsung',
                'is_active' => true, 'sales_count' => 55,
            ],
            [
                'name' => 'Apple Watch Ultra 2',
                'description' => 'ساعة آبل الذكية للرياضيين والمغامرين',
                'price' => 799.00, 'stock' => 12, 'category' => 'accessories', 'brand' => 'apple',
                'is_active' => true, 'sales_count' => 30,
            ],
            [
                'name' => 'Samsung Galaxy Watch 6 Classic',
                'description' => 'ساعة سامسونج الذكية بإطار دوار كلاسيكي',
                'price' => 399.00, 'stock' => 15, 'category' => 'accessories', 'brand' => 'samsung',
                'is_active' => true, 'sales_count' => 25,
            ],
            [
                'name' => 'JBL Flip 6 Speaker',
                'description' => 'سماعة بلوتوث محمولة مقاومة للماء',
                'price' => 129.00, 'stock' => 45, 'category' => 'accessories', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 60,
            ],
            [
                'name' => 'Logitech MX Master 3S',
                'description' => 'ماوس لاسلكي احترافي مع عجلة مغناطيسية',
                'price' => 99.00, 'stock' => 30, 'category' => 'accessories', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 40,
            ],
            [
                'name' => 'Anker PowerCore 20K',
                'description' => 'بطارية متنقلة بسعة 20000mAh',
                'price' => 49.00, 'stock' => 80, 'category' => 'accessories', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 95,
            ],
            [
                'name' => 'Razer BlackWidow V4',
                'description' => 'كيبورد ميكانيكي للألعاب بإضاءة RGB',
                'price' => 189.00, 'stock' => 18, 'category' => 'accessories', 'brand' => 'other',
                'is_active' => true, 'sales_count' => 22,
            ],
        ];

        // مصفوفة روابط صور حقيقية ومباشرة من الإنترنت لكل فئة
        $categoryImages = [
            'smartphones' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500&auto=format&fit=crop&q=60',
            'laptops'     => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=500&auto=format&fit=crop&q=60',
            'tablets'     => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=500&auto=format&fit=crop&q=60',
            'smart-home'  => 'https://images.unsplash.com/photo-1558002038-1055907df827?w=500&auto=format&fit=crop&q=60',
            'accessories' => 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&auto=format&fit=crop&q=60',
        ];

        foreach ($products as $product) {
            // ✅ تخطي المنتج إذا كان موجوداً مسبقاً بنفس الاسم
            if (Product::where('name', $product['name'])->exists()) {
                continue;
            }

            // إعطاء رابط الصورة المناسب بناءً على القسم (Category)
            $product['image'] = $categoryImages[$product['category']] ?? 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=500';

            $product['slug'] = Str::slug($product['name']);

            // التأكد من عدم تكرار slug
            $count = 1;
            $originalSlug = $product['slug'];
            while (Product::where('slug', $product['slug'])->exists()) {
                $product['slug'] = $originalSlug . '-' . $count;
                $count++;
            }

            Product::create($product);
        }

        $this->command->info('✅ تم إضافة المنتجات الجديدة بنجاح مع الصور');
    }
}
