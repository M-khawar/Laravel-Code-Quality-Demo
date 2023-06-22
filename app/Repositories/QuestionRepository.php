<?php

namespace App\Repositories;

use App\Contracts\Repositories\QuestionRepositoryInterface;
use App\Models\Question;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class QuestionRepository implements QuestionRepositoryInterface
{

    private Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function getQuestionByUuid($uuid)
    {
        return Question::findByUuid($uuid);
    }

    public function all()
    {
        $query = $this->model::query();
        $query->with(['answer' => fn($q) => $q->filterByUser(auth()->id())]);
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
}
