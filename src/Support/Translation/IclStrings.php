<?php

namespace Presspack\Framework\Support\Translation;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;

class IclStrings extends Model
{
    protected $table = 'icl_strings';
    protected $guarded = [];
    public $timestamps = false;
    protected $with = ['translations'];

    public function translations()
    {
        return $this->hasMany(StringTranslation::class, 'string_id');
    }
}
