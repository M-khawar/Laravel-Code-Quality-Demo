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
            $table->enum('funnel_type', [MASTER_FUNNEL, LIVE_OPPORTUNITY_CALL_FUNNEL])->nullable();
            $table->enum('status', [LEAD_ACTIVE, LEAD_IN_ACTIVE])->default(LEAD_ACTIVE);
            $table->timestamps();
            $table->softDeletes();
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
