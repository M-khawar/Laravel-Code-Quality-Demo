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
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string("ip")->nullable();
            $table->string("user_agent", 300)->nullable();
            $table->foreignId('affiliate_id')->constrained('users');
            $table->enum("funnel_type", [MASTER_FUNNEL, LIVE_OPPORTUNITY_CALL_FUNNEL]);
            $table->enum("funnel_step", [WELCOME_FUNNEL_STEP, WEBINAR_FUNNEL_STEP, CHECKOUT_FUNNEL_STEP, THANKYOU_FUNNEL_STEP]);
            $table->timestamps();

            $table->index(['ip', 'funnel_type', 'funnel_step']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_views');
    }
};
