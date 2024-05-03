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
        Schema::create('Hotelsnearby', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('stars');
            $table->string('image_url');
            $table->unsignedBigInteger('destination_id');
            $table->foreign('destination_id')->references('id')->on('destination')->onDelete('cascade');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Hotelsnearby');
    }
};
