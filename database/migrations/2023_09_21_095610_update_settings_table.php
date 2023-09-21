<?php

use App\Models\{Setting, User};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::table("settings", function (Blueprint $table) {
            $table->foreignId("user_id")->nullable()->change();
        });

        $adminSettings = config("settings.admin");
        $settings = [];

        foreach ($adminSettings as $key => $value) {
            [$group, $property] = (new User)->splitKey($key);

            $settings[] = [
                "group" => $group,
                "name" => $property,
                "value" => $value
            ];
        }

        Setting::insert($settings);
    }


    public function down()
    {
        Setting::where("group", "admin_settings")->delete();
    }
};
