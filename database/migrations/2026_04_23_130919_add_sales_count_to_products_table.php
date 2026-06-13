<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // ✅ أضف العمودين دول
            if (!Schema::hasColumn('products', 'sales_count')) {
                $table->integer('sales_count')->default(0)->after('is_active');
            }
            if (!Schema::hasColumn('products', 'specifications')) {
                $table->json('specifications')->nullable()->after('sales_count');
            }
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sales_count', 'specifications']);
        });
    }
};
