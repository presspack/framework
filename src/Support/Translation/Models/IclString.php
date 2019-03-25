<?php

namespace Presspack\Framework\Support\Translation\Models;

use Illuminate\Database\Eloquent\Model;

class IclString extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    protected $with = ['translations'];

    public function translations()
    {
        return $this->hasMany(IclStringTranslation::class, 'string_id');
    }
}
