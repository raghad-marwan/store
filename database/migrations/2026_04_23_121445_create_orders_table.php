
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();     // رقم الطلب #2241
            $table->decimal('total', 10, 2);             // المبلغ الإجمالي
            $table->enum('status', [
                'pending',      // بانتظار الدفع
                'processing',   // قيد التجهيز
                'shipped',      // تم الشحن
                'delivered',    // تم التوصيل
                'cancelled'     // ملغي
            ])->default('pending');
            $table->string('customer_name');             // اسم العميل
            $table->string('phone');                     // رقم الهاتف
            $table->text('address');                     // عنوان الشحن
            $table->text('notes')->nullable();           // ملاحظات
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
