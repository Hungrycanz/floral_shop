<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('status', ['pending', 'confirmed', 'delivered', 'cancelled'])
                ->default('pending');
            $table->string('recipient_name', 100);
            $table->string('recipient_phone', 20);
            $table->string('delivery_address', 255);
            $table->date('delivery_date'); // floral orders are scheduled, unlike Milk Shop
            $table->text('card_message')->nullable();
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->timestamp('order_date')->useCurrent();

            $table->index('user_id');
            $table->index('delivery_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
