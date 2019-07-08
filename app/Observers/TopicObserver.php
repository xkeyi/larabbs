<?php

namespace App\Observers;

use App\Models\Topic;

class TopicObserver
{
    // creating, created, updating, updated, saving,
    // saved,  deleting, deleted, restoring, restored

    public function saving(Topic $topic)
    {
        $topic->body = clean($topic->body);

        $topic->excerpt = make_excerpt($topic->body);
    }
}
