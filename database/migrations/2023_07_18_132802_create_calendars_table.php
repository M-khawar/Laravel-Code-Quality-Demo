<?php

use App\Contracts\Repositories\CalendarRepositoryInterface;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function __construct(private CalendarRepositoryInterface $calendarRepository)
    {
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('title');
            $table->longText('description');
            $table->string('link');
            $table->enum('color', $this->calendarRepository->calenderColors());
            $table->dateTime('calendar_timestamp');
            $table->date('display_date');
            $table->time('start_time');
            $table->time('end_time');
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
        Schema::dropIfExists('calendars');
    }
};
