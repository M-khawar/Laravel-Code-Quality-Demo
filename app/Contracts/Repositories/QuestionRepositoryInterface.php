<?php

namespace App\Contracts\Repositories;

interface QuestionRepositoryInterface
{
    public function getQuestionByUuid($uuid);

    public function all();

    public function storeAnswerValidation(array $data);

    public function storeQuestion(array $data);

}
