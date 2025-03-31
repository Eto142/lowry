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
        Schema::create('artworks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('artist_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('exhibition_id')->nullable()->constrained('exhibitions')->onDelete('set null');
            $table->string('image_url')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('current_bid', 10, 2)->nullable();
            $table->boolean('is_auction')->default(false);
            $table->boolean('is_sold')->default(false);
            $table->timestamp('auction_end')->nullable();
            $table->string('medium')->nullable();
            $table->string('category')->nullable();
            $table->foreignId('current_bidder_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('buyer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artworks');
    }
};
