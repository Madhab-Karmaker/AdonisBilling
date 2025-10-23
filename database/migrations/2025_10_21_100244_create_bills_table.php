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
        Schema::create('bills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade'); // Link to organization
            $table->string('customer_name', 100); // Customer name
            $table->decimal('total_amount', 10, 2)->default(0); // Total amount for the bill
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Receptionist who created the bill
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bills');
    }
};
