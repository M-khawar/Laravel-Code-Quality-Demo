<?php

namespace App\Repositories;

use App\Models\Media;
use Illuminate\Support\Facades\Hash;
use App\Contracts\Repositories\{ProfileRepositoryInterface, UserRepositoryInterface};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Symfony\Component\HttpFoundation\Response;

class ProfileRepository implements ProfileRepositoryInterface
{
    private Model $userModel;
    private $userRepository;
    private $mediaModel;

    public function __construct(Model $userModel)
    {
        $this->userModel = $userModel;
        $this->userRepository = app(UserRepositoryInterface::class);
        $this->mediaModel = app(Media::class);
    }

    public function updateProfile(array $data)
    {
        $user = currentUser();

        if (!empty($data['avatar_uuid'])) {
            $media = $this->mediaModel::findOrFailByUuid($data['avatar_uuid']);
            $data["avatar_id"] = @$media->id;
        }

        $user->update($data);
        $user->refresh();

        $paypal = @$data["paypal"] ?? "";
        $user->updateProperty(ACCOUNT_SETTING_GROUP, PAYPAL_ACCOUNT_SETTING, $paypal);

        return $this->userRepository->getUserInfo($user);

    }

    public function updateProfileValidation(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:50'],
            'phone' => ['required', 'string'],
            'instagram' => ['nullable', 'string'],
            'paypal' => ['nullable', 'string'],
            'avatar_uuid' => ['nullable', 'uuid', 'exists:' . get_class($this->mediaModel) . ',uuid'],
        ]);
    }

    public function updatePassword(array $data)
    {
        $user = currentUser();
        $password = $data["password"];

        $validPassword = Hash::check($data["old_password"], $user->password);

        abort_if(!$validPassword, Response::HTTP_UNPROCESSABLE_ENTITY, __("auth.password_not_matched"));

        $passwordHash = Hash::make($password);
        return $user->fill(["password" => $passwordHash])->save();
    }

    public function updatePasswordValidation(array $data)
    {
        return Validator::make($data, [
            'old_password' => ['required', Rules\Password::defaults()],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    }

    public function updateAdvisorSetting(array $data)
    {
        $user= currentUser();
        $user->updateProperty(ADVISOR_SETTING_GROUP, SCHEDULING_LINK_ATTR, $data["scheduling_link"]);
        $user->updateProperty(ADVISOR_SETTING_GROUP, FB_ACCOUNT, $data["facebook_link"]);
        $user->updateProperty(ADVISOR_SETTING_GROUP, ADVISOR_MESSAGE, $data["advisor_message"]);

        $advisorSettings= $user->settingFilters(group: ADVISOR_SETTING_GROUP)->get();

        return $advisorSettings;
    }

    public function updateAdvisorSettingValidation(array $data)
    {
        return Validator::make($data, [
            'scheduling_link' => ['required', 'string', 'max:70'],
            'facebook_link' => ['nullable', 'string', 'max:70'],
            'advisor_message' => ['nullable', 'string', 'max:190'],
        ]);
    }

}
