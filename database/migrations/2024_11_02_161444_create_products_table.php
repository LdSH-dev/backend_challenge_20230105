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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->text('description')->nullable();
            $table->json('ingredients')->nullable();
            $table->float('nutritional_score')->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->timestamp('imported_t')->nullable();
            $table->enum('status', ['draft', 'trash', 'published'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
