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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();

            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('title');
            $table->text('description');
            $table->timestamp('start_datetime');
            $table->timestamp('end_datetime')->nullable();
            $table->enum('status',['pending','ongoing','completed','cancelled'])->default('pending');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
