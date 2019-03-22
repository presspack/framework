<?php

namespace Presspack\Framework;

use Carbon\Carbon;
use Corcel\Model\Post as BasePost;
use Illuminate\Support\Facades\App;
use Presspack\Framework\Support\Translation\Translation;

class Post extends BasePost
{
    protected $postType;

    public function __construct(array $attributes = [])
    {
        $this->append(['time_ago']);
        parent::__construct($attributes);
    }

    public function wpml()
    {
        return $this->hasOne(Translation::class, 'element_id');
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
        if ($lang) {
            App::setLocale($lang);
        }

        return $query->leftJoin('icl_translations AS tr', function ($join) {
            $join->on('posts.ID', '=', 'tr.element_id')
                ->where('tr.element_type', '=', "post_{$this->postType}");
        })
            ->where('tr.language_code', '=', App::getLocale())
            ->get();
    }

    public function translate($lang = null)
    {
        if ($lang) {
            App::setLocale($lang);
        }
        $element = Translation::where('element_id', $this->attributes['ID'])->first();
        $translations = Translation::where('trid', $element->trid)->where('language_code', App::getLocale())->first();
        if (empty($translations)) {
            $translations = Translation::where('trid', $element->trid)->where('source_language_code', null)->first();
        }
        // Getting Post Object
        return self::find($translations->element_id);
    }
}
