<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid()->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('instagram')->nullable();
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->string('affiliate_code')->nullable()->index();
            $table->foreignId('advisor_id')->nullable()->constrained('users');

            $table->boolean('is_admin')->default('false');
            $table->boolean('is_advisor')->default('false');
            $table->boolean('is_active_recruiter')->default('false');
            $table->date('advisor_date')->nullable();

            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
