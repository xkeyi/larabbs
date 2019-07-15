<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    protected $fillable = ['title', 'link'];

    protected $cache_key = 'larabbs_links';
    protected $cache_expire_in_seconds = 24 * 60 * 60;

    public static function boot()
    {
        parent::boot();

        static::saved(function ($link) {
            Cache::forget($this->cache_key);
        });
    }

    public function getAllCached()
    {
        return Cache::remember($this->cache_key, $this->cache_expire_in_seconds, function () {
            return $this->all();
        });
    }
}
