<?php

use App\Http\Resources\ChatUserResource;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/


Broadcast::channel('global-chat', function ($user) {
    $user = currentUser();

    if ($user){
        return (new ChatUserResource($user));
    }

    return false;
});
