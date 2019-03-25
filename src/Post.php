<?php

namespace Presspack\Framework;

use Carbon\Carbon;
use Corcel\Model\Post as BasePost;
use Illuminate\Support\Facades\App;
use Presspack\Framework\Support\Translation\Models\IclTranslation;

class Post extends BasePost
{
    protected $postType = 'post';

    public function __construct(array $attributes = [])
    {
        $this->append(['time_ago']);
        parent::__construct($attributes);
    }

    public function wpml()
    {
        return $this->hasOne(IclTranslation::class, 'element_id');
    }

    public function getTimeAgoAttribute()
    {
        return Carbon::parse($this->attributes['post_date'])->diffForHumans();
    }

    public function getLanguageAttribute()
    {
        return $this->wpml->language_code;
    }

    public function scopeGetTranslated($query, $lang = null)
    {
        return $query->leftJoin('icl_translations AS tr', function ($join) {
            $join->on('posts.ID', '=', 'tr.element_id')
                ->where('tr.element_type', '=', "post_{$this->postType}");
        })
            ->where('tr.language_code', '=', $lang ?: App::getLocale())
            ->get();
    }

    public function translate($lang = null)
    {
        $element = IclTranslation::where('element_id', $this->attributes['ID'])->first();

        $translations = IclTranslation::where('trid', $element->trid)
            ->where('language_code', $lang ?: App::getLocale())
            ->first();

        if (empty($translations)) {
            $translations = IclTranslation::where('trid', $element->trid)->where('source_language_code', null)->first();
        }
        // Getting Post Object
        return self::find($translations->element_id);
    }
}
