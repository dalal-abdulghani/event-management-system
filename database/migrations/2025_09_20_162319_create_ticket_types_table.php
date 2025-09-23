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
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('name'); // General, VIP, Early Bird
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity_total');
            $table->integer('quantity_available');
            $table->datetime('sale_start')->nullable();
            $table->datetime('sale_end')->nullable();
            $table->integer('min_purchase')->default(1);
            $table->integer('max_purchase')->default(10);
            $table->boolean('is_active')->default(true);
            $table->json('benefits')->nullable(); // what's included with this ticket type
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};
