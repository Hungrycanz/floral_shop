<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Laravel's enum() renders an inline CHECK on Postgres; ALTERing an
        // enum column type in one statement is invalid there, so widen to a
        // plain varchar (values are enforced by app code)...
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 255)->default('customer')->change();
        });

        // ...and drop the stale CHECK constraint that still says customer/admin.
        $this->dropRoleCheckConstraint();
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['customer', 'admin', 'courier'])
                ->default('customer')
                ->change();
        });
    }

    private function dropRoleCheckConstraint(): void
    {
        if (DB::connection()->getDriverName() !== 'pgsql') {
            return;
        }

        $constraints = DB::select(
            'select con.conname from pg_constraint con'
            .' join pg_class rel on rel.oid = con.conrelid'
            .' join pg_attribute att on att.attrelid = con.conrelid and att.attnum = any(con.conkey)'
            ." where rel.relname = 'users' and att.attname = 'role' and con.contype = 'c'"
        );

        foreach ($constraints as $constraint) {
            DB::statement('alter table users drop constraint '.$constraint->conname);
        }
    }
};
