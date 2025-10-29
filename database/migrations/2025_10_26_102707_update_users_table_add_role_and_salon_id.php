<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            // Add a string role instead of enum
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('receptionist')->after('password');
            }

            if (!Schema::hasColumn('users', 'salon_id')) {
                $table->foreignId('salon_id')->nullable()->after('role')
                      ->constrained('salons')->onDelete('cascade');
            }
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'salon_id')) {
                $table->dropForeign(['salon_id']);
                $table->dropColumn('salon_id');
            }

            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
        });
    }
};
