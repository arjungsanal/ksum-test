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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            // Form Data Fields
            $table->string('full_name');
            $table->string('email')->unique();
            $table->string('phone');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('date_of_birth');
            $table->text('short_bio');
            $table->string('resume_path'); // Stores the file path

            // Payment Fields
            $table->string('razorpay_order_id')->nullable()->unique();
            $table->string('razorpay_payment_id')->nullable()->unique();
            $table->string('status')->default('pending'); // pending, paid, failed

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
