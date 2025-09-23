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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // referrer
            $table->foreignId('event_id')->nullable()->constrained()->onDelete('cascade'); // specific event or null for all
            $table->decimal('commission_rate', 5, 2)->default(0); // percentage commission
            $table->decimal('commission_earned', 10, 2)->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('conversions')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
