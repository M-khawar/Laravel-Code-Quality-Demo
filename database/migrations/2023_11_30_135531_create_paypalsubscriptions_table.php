<?php 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaypalSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypalsubscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name')->default('Membership_Subscription');
            $table->integer('quantity')->default(1);
            $table->unsignedBigInteger('subscription_plan_id');
            $table->string('paypal_status');
            $table->string('interval');
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'subscription_plan_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('paypalsubscriptions');
    }
}
