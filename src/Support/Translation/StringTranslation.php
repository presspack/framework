<?php

namespace Presspack\Framework\Support\Translation;

use Illuminate\Database\Eloquent\Model;

class StringTranslation extends Model
{
    protected $table = 'icl_string_translations';
    protected $guarded = [];
    public $timestamps = false;
}
