<?php

namespace Presspack\Framework\Support\Translation;

use Illuminate\Support\Facades\App;

class Strings
{
    /** @var \Illuminate\Support\Collection */
    protected $iclStrings;

    /** @var \Illuminate\Support\Collection */
    protected $string;

    /** @var string */
    protected $translated;

    /** @var string */
    protected $locale;

    /** @var bool */
    protected $localeIsDefault = true;

    public function __construct()
    {
        if (App::getLocale() != config('presspack.default_locale')) {
            $this->localeIsDefault = false;

            return $this->iclStrings = IclStrings::where('context', 'presspack')->get();
        }
    }

    public function get(string $string, string $locale = null): string
    {
        $this->locale = $locale ?: App::getLocale();
        $this->translated = $string;

        if ($this->localeIsDefault) {
            return $this->translated;
        }

        return $this->getTranslated();
    }

    public function getTranslated(): string
    {
        if ($this->checkIfExists()) {
            $translation = $this->string->translations->where('language', $this->locale)->first();

            if (isset($translation->value) && 10 == $translation->status) {
                $this->translated = $translation->value;
            }
        }

        return $this->translated;
    }

    public function checkIfExists(): bool
    {
        $this->string = $this->iclStrings->where('name', md5($this->translated))->first();

        if (! $this->string) {
            $this->addString($this->translated);

            return false;
        }

        return true;
    }

    public function addString($string)
    {
        IclStrings::create([
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
