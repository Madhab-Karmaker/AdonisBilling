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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade'); // Link to organization
            $table->string('name', 100); // Service name (Haircut, Manicure, etc.)
            $table->string('allowed_roles', 255)->nullable(); // Roles allowed to perform this service (comma-separated)
            $table->decimal('base_price', 10, 2)->default(0); // Default price
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
