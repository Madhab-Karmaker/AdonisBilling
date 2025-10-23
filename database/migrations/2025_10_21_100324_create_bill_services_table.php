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
        Schema::create('bill_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade'); // Salon
            $table->foreignId('bill_id')->constrained()->onDelete('cascade'); // Links to bill
            $table->foreignId('service_id')->constrained()->onDelete('cascade'); // Service provided
            $table->decimal('total_price', 10, 2)->default(0); // Total price for this service
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bill_services');
    }
};
