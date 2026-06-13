// database/migrations/xxxx_xx_xx_xxxxxx_create_order_items_table.php

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('product_name');     // اسم المنتج وقت الطلب
            $table->integer('quantity');        // الكمية
            $table->decimal('price', 10, 2);   // سعر الوحدة
            $table->decimal('subtotal', 10, 2); // الإجمالي الجزئي
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
};
