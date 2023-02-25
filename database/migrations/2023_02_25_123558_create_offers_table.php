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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->integer('offer_id');
            $table->string('offer_title');
            $table->string('author')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('category_title')->nullable();
            $table->double('min_order')->nullable();
            $table->integer('offer_rate')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
