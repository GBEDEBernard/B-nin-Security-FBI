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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignId('agency_id')->constrained('agencies')->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->string('name');
            $table->string('location');
            $table->text('description')->nullable();
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('required_agents')->default(1);
            $table->decimal('rate_per_agent', 10, 2);
            $table->date('shift_date');
            $table->enum('type', ['regular', 'special_event'])->default('regular');
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
