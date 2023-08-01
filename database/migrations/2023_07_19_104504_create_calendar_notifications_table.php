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
        Schema::create('calendar_notifications', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('calendar_id')->constrained('calendars')->cascadeOnDelete();
            $table->enum('type', [SMS_NOTIF_TYPE, MAIL_NOTIF_TYPE]);
            $table->string('duration');
            $table->enum('duration_type', ['minutes', 'hour'])->default('minutes');
            $table->boolean('sent_status')->default(false);
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
        Schema::dropIfExists('calendar_notifications');
    }
};
