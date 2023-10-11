<?php

namespace Database\Seeders\Production;

use Illuminate\Database\Seeder;
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
}
