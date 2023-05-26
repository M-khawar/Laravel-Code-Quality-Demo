<?php

namespace App\Traits;

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
}
