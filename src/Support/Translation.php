<?php

namespace Presspack\Framework\Support;

use Presspack\Framework\Post;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $table = 'icl_translations';
    protected $primaryKey = 'translation_id';

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
