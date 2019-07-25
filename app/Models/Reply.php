<?php

namespace App\Models;

use App\Notifications\TopicReplied;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    protected $fillable = ['content'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($reply) {
            $reply->content = clean($reply->content, 'user_topic_body');
        });

        static::created(function ($reply) {
            // $reply->topic->increment('reply_count', 1);
            $reply->topic->updateReplyCount();

            // 如果评论的作者不是话题的作者，才需要通知
            if (!$reply->user->isAuthorOf($reply->topic)) {
                // 通知话题作者又新的评论
                $reply->topic->user->notify(new TopicReplied($reply));
            }
        });

        static::deleted(function ($reply) {
            $reply->topic->updateReplyCount();
        });
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeRecent($query)
    {
        // 按照创建时间排序
        return $query->orderBy('created_at', 'desc');
    }
}
