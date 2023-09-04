<?php

namespace App\Repositories;

use App\Contracts\Repositories\ProfileRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class ProfileRepository implements ProfileRepositoryInterface
{
    private Model $userModel;

    public function __construct(Model $userModel)
    {
        $this->userModel = $userModel;
    }
}
