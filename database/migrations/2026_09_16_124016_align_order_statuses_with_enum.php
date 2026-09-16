<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('ALTER TABLE orders DROP CONSTRAINT orders_status_check');
        DB::statement("ALTER TABLE orders ALTER COLUMN status SET DEFAULT 'placed'");
        DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_status_check CHECK (status::text = ANY (ARRAY['placed','confirmed','out_for_pickup','out_for_delivery','delivered','cancelled']::text[]))");
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement('ALTER TABLE orders DROP CONSTRAINT orders_status_check');
        DB::statement("ALTER TABLE orders ALTER COLUMN status SET DEFAULT 'pending'");
        DB::statement("ALTER TABLE orders ADD CONSTRAINT orders_status_check CHECK (status::text = ANY (ARRAY['pending','confirmed','delivered','cancelled']::text[]))");
    }
};
