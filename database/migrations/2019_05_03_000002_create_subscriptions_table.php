<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuid()->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('subscription_plan_id')->nullable();
            $table->string('name');
            $table->string('stripe_id')->nullable()->unique();
            $table->string('stripe_status');
            $table->string('stripe_price')->nullable();
            $table->string('interval')->nullable();
            $table->integer('stripe_update')->nullable();
            $table->integer('quantity')->nullable();
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'stripe_status', 'subscription_plan_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
