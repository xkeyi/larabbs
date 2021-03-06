<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;
use Illuminate\Auth\Access\HandlesAuthorization;

class TopicPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Topic $topic)
    {
        return $user->id === $topic->user_id || $user->can('manage_contents');
    }

    public function destroy(User $user, Topic $topic)
    {
        return $user->isAuthorOf($topic) || $user->can('manage_contents');
    }
}
