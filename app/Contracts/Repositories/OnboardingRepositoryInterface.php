<?php

namespace App\Contracts\Repositories;

use App\Models\User;

interface OnboardingRepositoryInterface
{
    public function getQuestionByUuid($uuid);

    public function all(?string $userUuid);

    public function storeAnswerValidation(array $data);

    public function storeQuestion(array $data);

    public function markStepStatus(array $data);

    public function onboardingStepsState(?User $user = null): array;

    public function storeNote(array $data);

    public function storeNoteValidation($data);

    public function editNote(array $data);

    public function editNoteValidation($data);

    public function deleteNote(string $uuid);
}
