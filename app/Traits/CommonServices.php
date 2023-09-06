<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ramsey\Uuid\Uuid;

trait CommonServices
{
    public function generateUniqueUUID($table = null, $uuidColumnName = 'uuid', $excludedUuids = [])
    {
        $data['uuid'] = Uuid::uuid4()->toString();
        $validation = Validator::make($data, [
            $uuidColumnName => ["unique:$table", Rule::notIn($excludedUuids)]
        ]);

        if ($validation->fails()) {
            $this->generateUniqueUUID($table);
        }

        return $data['uuid'];
    }

    public function doEncrypt(string $string): string
    {
        if (strlen(trim($string)) === 0) {
            throw new \InvalidArgumentException("Empty string given for encryption");
        }

        return base64_encode(encrypt($string));
    }

    public function doDecrypt(string $string): string
    {
        if (strlen(trim($string)) < 100) {
            throw new \InvalidArgumentException("Invalid string given for decryption");
        }

        return decrypt(base64_decode($string));
    }

    public function periodConversion(string $period, ?array $data = []): object
    {
        $start_date = $end_date = null;

        switch ($period) {
            case "today":
                $start_date = now()->startOfDay();
                $end_date = now()->endOfDay();
                break;

            case "yesterday":
                $start_date = now()->subDay(1)->startOfDay();
                $end_date = now()->subDay(1)->endOfDay();
                break;

            case "last_seven":
                $start_date = now()->subDay(7)->startOfDay();
                $end_date = now()->endOfDay();
                break;

            case "last_fourteen":
                $start_date = now()->subDay(14)->startOfDay();
                $end_date = now()->endOfDay();
                break;

            case "this_month":
                $start_date = now()->startOfMonth();
                $end_date = now()->endOfMonth();
                break;

            case "last_month":
                $start_date = now()->subMonth(1)->startOfMonth()->startOfDay();
                $end_date = now()->subMonth(1)->endOfMonth()->endOfDay();
                break;

            case "last_three_month":
                $start_date = now()->subMonth(3)->startOfMonth()->startOfDay();
                $end_date = now()->endOfDay();
                break;

            case "last_six_month":
                $start_date = now()->subMonth(6)->startOfMonth()->startOfDay();
                $end_date = now()->endOfDay();
                break;

            case "last_twelve_month":
                $start_date = now()->subMonth(12)->startOfMonth()->startOfDay();
                $end_date = now()->endOfDay();
                break;

            case "this_year":
                $start_date = now()->startOfYear()->startOfDay();
                $end_date = now()->endOfDay();
                break;

            case "last_year":
                $start_date = now()->subYear(1)->startOfYear()->startOfDay();
                $end_date = now()->subYear(1)->endOfYear()->startOfDay();
                break;

            case "custom":
                $start_date = (new Carbon($data['start_date']))->startOfDay();
                $end_date = (new Carbon($data['end_date']))->endOfDay();
                break;

            case "all":
            default:
                $start_date = (new Carbon("2020-01-01 00:00:00"))->subDay(1);
                $end_date = now()->endOfDay();
                break;

        }

        return (object)compact('start_date', 'end_date');
    }
}
