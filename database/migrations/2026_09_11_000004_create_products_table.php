<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories');
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // NUMERIC in Postgres, never float - money
            $table->unsignedInteger('stock_quantity')->default(0);
            $table->string('image_url', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('created_at')->useCurrent();

            // Laravel's unsignedInteger already rejects negatives at the column
            // type level, but the source schema's explicit CHECK (price >= 0)
            // has no first-class Laravel equivalent for decimal columns -
            // enforce that one in a Form Request instead (see StoreProductRequest).
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
