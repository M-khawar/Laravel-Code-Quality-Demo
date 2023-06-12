<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReferralResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getReferral()
    {
        try {
            $referralID = request()->input('referral');

            $user = User::query()
                ->when(!empty($referralID), fn($q)=> $q->whereAffiliate($referralID))
                ->when(empty($referralID), fn($q)=>$q->whereDefaultAdvisor())
                ->with('profile')
                ->firstOrFail();

            $referral = new ReferralResource($user);
            return response()->success('Successfuly, Referral Found', $referral);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
