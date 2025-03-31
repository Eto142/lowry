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
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('buyer_name')->nullable();
            $table->string('seller_name')->nullable();
            $table->string('exhibition_status')->default('pending');
            $table->decimal('amount_sold', 10, 2)->nullable();
            $table->date('date')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->string('venue')->nullable();
            $table->string('image_url')->nullable();
            $table->string('artist_email')->nullable();
            $table->boolean('is_featured')->default(false);
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
