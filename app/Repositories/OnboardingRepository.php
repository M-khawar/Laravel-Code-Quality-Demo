<?php

namespace App\Repositories;

use App\Contracts\Repositories\OnboardingRepositoryInterface;
use App\Models\{Note, User};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class OnboardingRepository implements OnboardingRepositoryInterface
{

    private Model $model;
    private $noteModel;
    private $userModel;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->noteModel = app(Note::class);
        $this->userModel = app(User::class);
    }

    public function getQuestionByUuid($uuid)
    {
        return $this->model::findByUuid($uuid);
    }

    public function all(?string $userUuid)
    {
        $userId = $userUuid ? User::findOrFailUserByUuid($userUuid)?->id : currentUserId();

        $query = $this->model::query();
        $query->with([
            'video',
            'answer' => fn($q) => $q->filterByUser($userId)
        ]);
        return $query->get();
    }

    public function storeQuestion(array $data)
    {
        $question = $this->getQuestionByUuid($data['uuid']);

        return $question->answer()->firstOrCreate(
            ['question_id' => $question->id, 'user_id' => $data['user_id']], $data
        );
    }

    public function storeAnswerValidation($data)
    {
        return Validator::make($data, [
            'question_uid' => ['required', 'uuid', 'exists:questions,uuid'],
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

    public function storeNote(array $data)
    {
        $user= $this->userModel::findOrFailUserByUuid($data["questionnaire_user_uuid"]);
        $note= $user->notes()->create($data);
        return $note;
    }

    public function storeNoteValidation($data)
    {
        return Validator::make($data, [
            'questionnaire_user_uuid' => ['required', 'uuid', 'exists:' . get_class($this->userModel) . ',uuid'],
            'note' => ['required'],
        ]);
    }

    public function editNote(array $data)
    {
        $note= $this->noteModel::findOrFailNoteByUuid($data["note_uuid"]);
        $note->fill($data)->save();

        return $note;
    }

    public function editNoteValidation($data)
    {
        return Validator::make($data, [
            'note_uuid' => ['required', 'uuid', 'exists:' . get_class($this->noteModel) . ',uuid'],
            'note' => ['required'],
        ]);
    }

    public function deleteNote(string $uuid)
    {
        $note= $this->noteModel::findOrFailNoteByUuid($uuid);
        return $note->delete();
    }

    public function fetchNotes(string $uuid)
    {
        $user= $this->userModel::findOrFailUserByUuid($uuid);
        return $user->notes()->get();
    }
}
