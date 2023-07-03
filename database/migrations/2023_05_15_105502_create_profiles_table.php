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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->foreignId('user_id')->constrained('users');
            $table->string('display_name')->nullable();
            $table->text('display_text')->nullable();
            $table->string('head_code')->nullable();
            $table->string('body_code')->nullable();

            $table->boolean('is_enagic')->default(false);
            $table->boolean('questionnaire_completed')->default(false);
            $table->boolean('is_trifecta')->default(false);
            $table->boolean('is_core')->default(false);

            $table->date('enagic_data')->nullable();
            $table->date('trifecta_date')->nullable();
            $table->date('core_date')->nullable();

            /* $table->boolean('lead_email')->default(true);
            $table->boolean('lead_sms')->default(true);
            $table->boolean('mem_email')->default(true);
            $table->boolean('mem_sms')->default(true);
            $table->boolean('promote_watched')->default(false);
            $table->boolean('welcome_video')->default(false);
            $table->boolean('fb_group')->default(false);*/

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
        Schema::dropIfExists('profiles');
    }
};
