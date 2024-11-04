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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('firstname');
            $table->string('middlename');
            $table->string('lastname');
            $table->string('address');
            $table->string('contact');
            $table->string('email')->nullable();
            $table->string('status')->default('For review');
            $table->string('tracking_no')->unique();
            $table->text('message')->nullable();
            $table->timestamps();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
