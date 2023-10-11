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
            ->selectRaw("*, (select count(*) from user_forms where user_forms.user_id = users.id and user_forms.form_id = 1 limit 1) as meeting_scheduled")
            ->get();

        $rawUsers = $users->map(function ($user) {
            return $this->buildUser($user);
        });

        collect($rawUsers)->each(function ($user) {
            $this->storeUser($user);
        });

        $this->enableForeignKeys();
    }

    private function storeUser(array $userData)
    {
//        dump($userData);
        $profile = $userData["profile"];
        $address = $userData["address"];
        $roles = $userData["roles"];
        $onboarding = $userData["onboarding"];

        unset($userData["profile"], $userData["address"], $userData["roles"], $userData["onboarding"]);

        $this->storeAvatar($userData);
        $email = $userData["email"];
        unset($userData["email"]);

        $user = User::firstOrCreate([...$userData], ["email" => $email]);
        $user->profile()->updateOrCreate($profile);
        $user->address()->updateOrCreate($address);
        $user->assignRole($roles);

        event(new Registered($user));
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
        $created_at = Carbon::createFromTimestamp($timestamp)->toDateTimeString();
//        $created_at = substr(Carbon::createFromTimestamp($timestamp), 0, -3);

        return [
            'name' => $user->name,
            'email' => $user->email,
            'password' => $user->password,
            'instagram' => $user->instagram,
            'phone' => $user->phone,
            'affiliate_code' => $user->affiliate_code,
            'avatar' => $user->avatar,  //logic
            'funnel_type' => $user->funnel_id == 1 ? MASTER_FUNNEL : LIVE_OPPORTUNITY_CALL_FUNNEL,
//            'advisor_id' => $user->advisor_id,
//            'affiliate_id' => $user->affiliate_id,
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
