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
            $table->string('name');
            $table->string('address');
            $table->string('contact');
            $table->string('email')->nullable();
            $table->string('status')->default('For review');
            $table->string('tracking_no')->unique();
            $table->text('message')->nullable();
            $table->json('files_path')->default("[]");
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
