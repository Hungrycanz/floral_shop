<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // A bouquet can suit several occasions - pure M:N join table, no own id
        Schema::create('product_occasions', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('occasion_id')->constrained('occasions')->cascadeOnDelete();
            $table->primary(['product_id', 'occasion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_occasions');
    }
};
