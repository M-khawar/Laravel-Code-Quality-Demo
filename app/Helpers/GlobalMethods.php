<?php

use Illuminate\Support\Facades\Auth;

function currentUser()
{
    if (Auth::check()) {
        return Auth::user();
    }

    return null;
}

function currentUserId()
{
    if (Auth::check()) {
        return Auth::id();
    }

    return null;
}

function generateVideoSlug()
{
    return "v-" . rand(111111, 99999);
}
