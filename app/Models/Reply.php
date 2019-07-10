<?php

namespace App\Models;

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
            $reply->topic->reply_count = $reply->topic->replies->count();
            $reply->topic->save();
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
