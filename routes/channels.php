<?php

use Illuminate\Support\Facades\Broadcast;

// A user can only listen to their own inbox channel
Broadcast::channel('inbox.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});