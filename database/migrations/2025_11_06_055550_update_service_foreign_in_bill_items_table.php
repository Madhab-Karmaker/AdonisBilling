<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('bill_items', function (Blueprint $table) {
            // Make sure service_id can be nullable
            $table->unsignedBigInteger('service_id')->nullable()->change();
        });

        // Drop foreign key manually if it exists
        $foreignKeys = DB::select('SHOW CREATE TABLE bill_items');
        $createSql = $foreignKeys[0]->{'Create Table'};

        if (strpos($createSql, 'CONSTRAINT `bill_items_service_id_foreign`') !== false) {
            Schema::table('bill_items', function (Blueprint $table) {
                $table->dropForeign(['service_id']);
            });
        }

        // Add new foreign key with SET NULL
        Schema::table('bill_items', function (Blueprint $table) {
            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('bill_items', function (Blueprint $table) {
            $table->dropForeign(['service_id']);
            $table->foreign('service_id')
                ->references('id')
                ->on('services')
                ->cascadeOnDelete();
        });
    }
};
