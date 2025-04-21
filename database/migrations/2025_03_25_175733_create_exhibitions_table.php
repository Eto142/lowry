<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exhibitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->string('picture')->nullable();
            $table->string('picture_url')->nullable();
            $table->string('picture_public_id')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();

            // Exhibition date
            $table->date('date')->nullable();

            // Buyer information
            $table->string('buyer_name')->nullable();
            $table->string('buyer_email')->nullable();
            $table->string('buyer_phone')->nullable();
            $table->string('buyer_address')->nullable();
            $table->boolean('show_buyer_contact')->default(true);

            // Seller information
            $table->string('seller_name')->nullable();
            $table->string('seller_email')->nullable();
            $table->string('seller_phone')->nullable();
            $table->string('seller_address')->nullable();
            $table->boolean('show_seller_contact')->default(true);

            // Exhibition status and type
            $table->string('exhibition_status')->default('pending');
            $table->enum('exhibition_type', ['past', 'current', 'future'])->default('future');

            $table->string('amount_sold')->nullable();
            $table->boolean('is_featured')->default(true);
            $table->timestamps();

            // Add foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
        });

        // Add CHECK constraint using raw SQL
        DB::statement('ALTER TABLE exhibitions ADD CONSTRAINT user_or_admin_check CHECK ((user_id IS NOT NULL) OR (admin_id IS NOT NULL))');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhibitions');
    }
};
