<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

abstract class ConfigureDatabase extends Seeder
{
    private $connection;

    public function __construct()
    {
        $this->setConnection();
    }

    protected function getConnection()
    {
        return $this->connection;
    }

    protected function setConnection($connectionName = "importable_pgsql")
    {
        $this->connection = DB::connection($connectionName);
    }

    protected function timeStampConversion($timestamp)
    {
        return Carbon::createFromTimestamp($timestamp)->toDateTimeString();
//        $created_at = substr(Carbon::createFromTimestamp($timestamp), 0, -3);
    }
}
