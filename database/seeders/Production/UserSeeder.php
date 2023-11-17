<?php

namespace Database\Seeders\Production;

use App\Models\Media;
use App\Models\User;
use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Carbon;

class UserSeeder extends ConfigureDatabase
{
    use DisableForeignKeys, TruncateTable;

    public function run()
    {
        $this->disableForeignKeys();
        $this->truncateMultiple(["users", "addresses", "profiles", "model_has_roles"]);

        $users = $this->getConnection()
            ->table("users")
            ->distinct("email")
            ->selectRaw("*,
                (select count(*) from user_forms where user_forms.user_id = users.id and user_forms.form_id = 1 limit 1) as meeting_scheduled,
                (select aff.email from users aff where aff.id = users.affiliate_id limit 1) as affiliate_email,
                (select adv.email from users adv where adv.id = users.advisor_id limit 1) as advisor_email
            ")->get();
             

//        $users = $users->take(10);
// dump($userData);
        $rawUsers = $users->map(function ($user) {
            return $this->buildUser($user);
        });
        // dd($rawUsers);
        collect($rawUsers)->each(function ($user) {
            $this->storeUser($user);
        });
// dd()
        collect($rawUsers)->each(function ($user) {
            $this->assignAdministration($user);
        });

        $this->enableForeignKeys();
    }

    private function assignAdministration(array $user)
    {
        $affiliate = $user["affiliate_email"];
        $advisor = $user["advisor_email"];
        $user = $user["email"];

        $users = User::whereIn("email", [$affiliate, $advisor, $user])->get();

        $user = $users->where("email", $user)->first();
        $affiliate = $users->where("email", $affiliate)->first();
        $advisor = $users->where("email", $advisor)->first();

        if (!$user || !$affiliate || !$advisor) return;

        $user->update(["affiliate_id" => $affiliate->id, "advisor_id" => $advisor->id]);
    }

    private function storeUser(array $userData)
    {
    //    dump($userData);
        $profile = $userData["profile"];
        $address = $userData["address"];
        $roles = $userData["roles"];

        $onboarding = $userData["onboarding"];

        unset(
            $userData["profile"], $userData["address"], $userData["roles"], $userData["onboarding"],
            $userData["affiliate_email"], $userData["advisor_email"]
        );

        $this->storeAvatar($userData);
        $email = $userData["email"];
        unset($userData["email"]);

        $user = User::firstOrCreate([...$userData], ["email" => $email]);
        $user->profile()->updateOrCreate($profile);
        $user->address()->updateOrCreate($address);
        $user->assignRole($roles);

        event(new Registered($user));

        $user->updateMultipleProperties(ONBOARDING_GROUP_ALIAS, $onboarding);
    }

    private function storeAvatar(array &$user)
    {
        if (isset($user["avatar"])) {
            $media = Media::firstOrCreate(["path" => $user["avatar"]], ["source" => SPACES_STORAGE]);
            $user["avatar_id"] = $media->id;
        }

        unset($user["avatar"]);
    }

    private function buildUser($user)
    {
        $timestamp = (int)$user->created_at;
        $timestamp = intval($timestamp / 1000);
        $created_at = $this->timeStampConversion($timestamp);

        return [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'instagram' => $user->instagram,
            'phone' => $user->phone,
            'affiliate_code' => $user->affiliate_code,
            'avatar' => $user->avatar,  //logic
            'funnel_type' => $user->funnel_id == 1 ? MASTER_FUNNEL : LIVE_OPPORTUNITY_CALL_FUNNEL,
            'affiliate_email' => $user->affiliate_email,
            'advisor_email' => $user->advisor_email,
            'created_at' => $created_at,
            'updated_at' => $created_at,
            'profile' => [           //logic
                'display_name' => @$user->display_name,
                'display_text' => @$user->display_text,
                'head_code' => @$user->head_code,
                'body_code' => @$user->body_code,
            ],
            'address' => [                   //logic
                'city' => $user->city,
                'state' => $user->state,
                'zipcode' => $user->zip,
                'address' => $user->address,
            ],
            'roles' => $this->buildRoles($user),  //logic
            'onboarding' => [
                "welcome_video_watched" => @$user->welcome_video ?? false,
                "questionnaire_completed" => @$user->questionnaire_complete ?? false,
                "joined_facebook_group" => @$user->fb_group ?? false,
                "meeting_scheduled" => @$user->meeting_scheduled == 1 ? true : false,
            ]
        ];
    }

    private function buildRoles($user)
    {
        $roles = [ALL_MEMBER_ROLE];

        if (@$user->is_enagic) array_push($roles, ENAGIC_ROLE,);
        if (@$user->is_advisor) array_push($roles, ADVISOR_ROLE,);
        if (@$user->is_trifecta) array_push($roles, TRIFECTA_ROLE,);
        if (@$user->is_core) array_push($roles, CORE_ROLE);
        if (@$user->is_admin) array_push($roles, ADMIN_ROLE,);

        return $roles;
    }
}
