<?php

namespace Presspack\Framework\Support\Translation\Models;

use Presspack\Framework\Post;
use Illuminate\Database\Eloquent\Model;

class IclTranslation extends Model
{
    protected $primaryKey = 'translation_id';

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
