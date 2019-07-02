<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = ['title', 'body', 'category_id', 'excerpt', 'sulg'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
