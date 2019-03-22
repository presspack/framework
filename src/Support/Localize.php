<?php

namespace Presspack\Framework\Support;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class Localize
{
    public function supportedLocales()
    {
        $supported = DB::table('icl_languages')->where('active', '1')->pluck('code')->all();

        return $supported ?: config('localization.supported_locales');
    }

    public function routes($locale = null)
    {
        $locale = $locale ?: app()['request']->segment(1);
        $segments = Str::start(implode('/', app()['request']->segments()), '/');

        if (! $locale || ! \in_array($locale, $this->supportedLocales(), true)) {
            return header('Location: '.url(config('localization.default_locale').$segments));
        }

        App::setLocale($locale);

        return $locale;
    }

    public static function url(string $url)
    {
        return url(config('app.locale').Str::start($url, '/'));
    }
}
