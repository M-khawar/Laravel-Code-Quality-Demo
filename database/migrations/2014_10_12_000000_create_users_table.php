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
            $table->string('password')->nullable();
            $table->string('instagram')->nullable();
            $table->string('phone')->nullable();
            $table->foreignId('avatar_id')->nullable()->constrained('media')->nullOnDelete();
            $table->foreignId('advisor_id')->nullable()->constrained('users');
            $table->foreignId('affiliate_id')->nullable()->constrained('users');
            $table->string('affiliate_code')->nullable()->index();
            $table->string('funnel_type')->nullable();

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
