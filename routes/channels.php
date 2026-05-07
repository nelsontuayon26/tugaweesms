<?php

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

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('section.{sectionId}', function ($user, $sectionId) {
    // Teachers can listen to their sections
    if ($user->teacher) {
        return $user->teacher->sections()->where('sections.id', $sectionId)->exists();
    }
    // Students can listen to their own section
    if ($user->student) {
        return $user->student->section_id === (int) $sectionId;
    }
    return false;
});

// Private channel for user messages (for typing indicator and new messages)
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
