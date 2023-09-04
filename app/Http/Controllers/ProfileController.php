<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\ProfileRepositoryInterface;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(public ProfileRepositoryInterface $profileRepository)
    {
    }

    public function updateUserInfo(Request $request)
    {

    }

    public function updatePassword(Request $request)
    {

    }

    public function updateNotifications(Request $request)
    {

    }
}
