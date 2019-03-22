<?php

namespace Presspack\Framework\Support\Translation;

use Illuminate\Support\Facades\App;
use Illuminate\Database\Eloquent\Model;

class Strings extends Model
{
    protected $table = 'icl_strings';
    protected $guarded = [];
    public $timestamps = false;

    public function translations()
    {
        return $this->hasMany(StringTranslation::class, 'string_id');
    }

    public function get(string $string, string $locale = null)
    {
        $locale = $locale ?: App::getLocale();

        if ($locale == config('presspack.default_locale')) {
            return $string;
        }

        $table = $this->where('name', md5($string))
            ->where('context', 'presspack')
            ->first();

        if (! $table) {
            $this->addString($string);

            return $string;
        }

        $translation = $table->translations
            ->where('language', $locale)
            ->first();

        if (isset($translation->value) && 10 == $translation->status) {
            return $translation->value;
        }

        return $string;
    }

    public function addString($string)
    {
        $this->create([
            'language' => config('presspack.default_locale'),
            'context' => 'presspack',
            'name' => md5($string),
            'value' => $string,
            'type' => 'LINE',
            'status' => 0,
            'gettext_context' => '',
            'domain_name_context_md5' => md5('presspack'.md5($string)),
            'translation_priority' => '',
        ]);
    }
}
