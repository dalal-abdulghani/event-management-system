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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->string('slug')->unique();
            $table->foreignId('venue_id')->constrained()->onDelete('cascade');
            $table->foreignId('organizer_id')->constrained('users')->onDelete('cascade');
            $table->datetime('start_date');
            $table->datetime('end_date');
            $table->datetime('registration_start')->nullable();
            $table->datetime('registration_end')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->json('images')->nullable(); // array of image URLs
            $table->text('cancellation_policy')->nullable();
            $table->json('additional_info')->nullable(); // custom fields, requirements, etc.
            $table->boolean('is_featured')->default(false);
            $table->decimal('min_price', 10, 2)->nullable(); // for display purposes
            $table->decimal('max_price', 10, 2)->nullable(); // for display purposes
            $table->integer('total_capacity')->nullable();
            $table->integer('available_capacity')->nullable();
            $table->json('tags')->nullable(); // event categories/tags
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
