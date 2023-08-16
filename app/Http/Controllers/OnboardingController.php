<?php

namespace App\Http\Controllers;

use App\Contracts\Repositories\OnboardingRepositoryInterface;
use App\Http\Resources\QuestionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OnboardingController extends Controller
{


    public function __construct(public OnboardingRepositoryInterface $onboardingRepository)
    {
    }

    public function getQuestion()
    {
        try {
            $userUuid= request()->input('user');
            $questions = $this->onboardingRepository->all($userUuid);
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
            $userId = currentUserId();

            DB::beginTransaction();

            $this->onboardingRepository->storeAnswerValidation($input)->validate();

            $data = [
                'uuid' => $input['question_uid'],
                'user_id' => $userId,
                'text' => $input['answer'],
                'watched' => $input['video_watched']
            ];
            $answer = $this->onboardingRepository->storeQuestion($data);

            DB::commit();

            $message = $answer->wasRecentlyCreated ? __('messages.answer.stored') : __('messages.answer.existed');
            return response()->message($message);

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->handleException($exception);
        }
    }

    public function markStepStatus(Request $request)
    {
        try {
            $data = $request->input();

            $request->validate([
                'step_alias' => ['required', 'string'],
                'status' => ['required', 'boolean']
            ]);

            DB::beginTransaction();
            $onboardingStepsState = $this->onboardingRepository->markStepStatus($data);
            DB::commit();

            $message = __('messages.onboarding.step_updated', ['step' => $data['step_alias']]);
            return response()->success($message, $onboardingStepsState);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->handleException($e);
        }
    }

    public function getProgress()
    {
        try {
            $user = currentUser();
            $onboardingStepsState = $this->onboardingRepository->onboardingStepsState($user);
            return response()->success(__('messages.onboarding.steps_fetched'), $onboardingStepsState);

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

}
