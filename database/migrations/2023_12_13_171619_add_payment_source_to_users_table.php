<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('payment_source', ['card', 'apple_pay'])->default('card')->nullable();
        });
    }


    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('payment_source');
        });
    }
};
