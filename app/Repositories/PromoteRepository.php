<?php

namespace App\Repositories;

use App\Contracts\Repositories\PromoteRepositoryInterface;
use App\Models\Profile;
use Illuminate\Support\Facades\Validator;

class PromoteRepository implements PromoteRepositoryInterface
{

    private mixed $profile;

    public function __construct()
    {
        $this->profile = app(Profile::class);
    }

    public function updatePromoteSettings(int $userId, array $data)
    {
        $this->profile::where('user_id', $userId)->update($data);
        return $this->profile::where('user_id', $userId)->firstOrFail();
    }

    public function storeSettingValidation(array $data)
    {
        return Validator::make($data, [
            "display_name" => ["required"],
            "display_text" => ["required"],
            "head_code" => ["nullable"],
            "body_code" => ["nullable"],
        ]);
    }
}
