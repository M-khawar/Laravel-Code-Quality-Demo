<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('instagram')->nullable();
            $table->foreignId('advisor_id')->nullable()->constrained('users');
            $table->foreignId('affiliate_id')->nullable()->constrained('users');
            $table->enum('status', ['active', 'in_active'])->default('in_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('leads');
    }
};
