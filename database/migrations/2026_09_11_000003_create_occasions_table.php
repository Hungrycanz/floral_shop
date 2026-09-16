<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('occasions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique(); // e.g. Birthday, Wedding, Funeral, Anniversary
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('occasions');
    }
};
