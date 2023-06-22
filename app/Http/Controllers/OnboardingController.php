<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\QuestionRepositoryInterface;
use App\Http\Resources\QuestionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnboardingController extends Controller
{


    public function __construct(public QuestionRepositoryInterface $questionRepository)
    {
    }

    public function getQuestion()
    {
        try {
            $questions = $this->questionRepository->all();
            $questions = QuestionResource::collection($questions);

            return response()->success(__('question_fetched.success'), $questions);

        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }

    public function storeAnswer(Request $request)
    {
        try {
            $input = $request->input();

            DB::beginTransaction();

            $this->questionRepository->storeAnswerValidation($input)->validate();

            $data = [
                'uuid' => $input['question_uid'],
                'user_id' => auth()->id(),
                'text' => $input['answer'],
                'watched' => $input['video_watched']
            ];
            $answer = $this->questionRepository->storeQuestion($data);

            DB::commit();

            $message = $answer->wasRecentlyCreated ? __('messages.answer.stored') : __('messages.answer.existed');
            return response()->message($message);

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }


}
