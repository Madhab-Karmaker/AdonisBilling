<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bill_service_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bill_service_id')->constrained('bill_services')->onDelete('cascade'); // Links to bill_services
            $table->foreignId('staff_id')->constrained('users')->onDelete('cascade');               // Staff assigned to this service
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_service_staff');
    }
};
