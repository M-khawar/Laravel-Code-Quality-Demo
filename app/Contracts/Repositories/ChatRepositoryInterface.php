<?php

namespace App\Contracts\Repositories;

interface ChatRepositoryInterface
{
    public function fetchMessages();

    public function sendChatMessage(array $data);
}
