<?php

namespace App\Repositories;

use App\Contracts\Repositories\OnboardingRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OnboardingRepository implements OnboardingRepositoryInterface
{

    private Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getQuestionByUuid($uuid)
    {
        return $this->model::findByUuid($uuid);
    }

    public function all()
    {
        $query = $this->model::query();
        $query->with([
            'video',
            'answer' => fn($q) => $q->filterByUser(auth()->id())
        ]);
        return $query->get();
    }

    public function storeQuestion(array $data)
    {
        $question = $this->getQuestionByUuid($data['uuid']);

        return $question->answer()->firstOrCreate(
            ['question_id' => $question->id], $data
        );
    }

    public function storeAnswerValidation($data)
    {
        return Validator::make($data, [
            'question_uid' => ['required', 'exists:questions,uuid'],
            'answer' => [Rule::requiredIf($this->getQuestionByUuid($data['question_uid'])?->is_answerable), 'string', 'nullable'],
            'video_watched' => ['required', 'boolean'],
        ]);
    }

    public function markStepStatus(array $data)
    {
        $user = auth()->user();

        $group = ONBOARDING_GROUP_ALIAS;
        $step = $data['step_alias'];
        $status = $data['status'];

        $propertyExists = $user->checkIfPropertyExists($group, $step);
        throw_if(!$propertyExists, __('messages.onboarding.step_invalid', ['step' => $step]));

        $user->updateProperty($group, $step, $status);
        return $this->onboardingStepsState($user);
    }


    public function onboardingStepsState(?User $user = null): array
    {
        $user = $user ?? auth()->user();
        $stepsState = $user->getPropertiesInGroup(ONBOARDING_GROUP_ALIAS);

        $stepsData = [];
        foreach ($stepsState as $step) {
            $stepsData = array_merge($stepsData, [$step->name => (bool)$step->value]);
        }

        return $stepsData;
    }
}
