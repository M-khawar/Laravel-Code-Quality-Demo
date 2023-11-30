<?php

namespace Database\Seeders\Production;

use App\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdatePaypalUsers extends ConfigureDatabase
{
    use DisableForeignKeys, TruncateTable;

    public function run()
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('paypal_id')->nullable()->after('stripe_id');
    });

    $this->disableForeignKeys();

    // Update the 'paypal_id' column based on the 'stripe_id' column
    DB::table('users')
        ->update([
            'paypal_id' => DB::raw("CASE WHEN stripe_id LIKE 'Paypal:%' THEN SPLIT_PART(stripe_id, ':', 2) ELSE NULL END"),
            'stripe_id' => DB::raw("CASE WHEN stripe_id LIKE 'Paypal:%' THEN NULL ELSE stripe_id END"),
        ]);

    $this->enableForeignKeys();
}


}