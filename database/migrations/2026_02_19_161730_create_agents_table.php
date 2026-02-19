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
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('agency_id')->constrained('agencies')->onDelete('cascade');
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('id_number')->unique();
            $table->enum('id_type', ['passport', 'national_id', 'drivers_license'])->default('national_id');
            $table->date('date_of_birth')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();
            $table->string('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('position');
            $table->decimal('salary_per_day', 10, 2)->nullable();
            $table->date('hire_date');
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->date('termination_date')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->unique(['tenant_id', 'email']);
            $table->unique(['tenant_id', 'id_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
