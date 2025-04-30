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
        Schema::create('future_exhibitions', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('theme')->nullable();
            $table->text('description')->nullable();
            $table->string('mediums')->nullable();
            $table->text('objective')->nullable();
            $table->text('sections')->nullable(); // JSON or text field for sections
            $table->decimal('budget', 10, 2)->nullable();
            $table->date('exhibition_date')->nullable();
            $table->enum('type', ['future', 'current'])->default('future');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('future_exhibitions');
    }
};
