<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('courier_id')->nullable()->after('user_id')
                ->constrained('users')->nullOnDelete();
            $table->foreignId('delivery_zone_id')->nullable()->after('status')
                ->constrained('delivery_zones')->nullOnDelete();
            $table->timestamp('delivered_at')->nullable()->after('order_date');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropConstrainedForeignId('courier_id');
            $table->dropConstrainedForeignId('delivery_zone_id');
            $table->dropColumn('delivered_at');
        });
    }
};
