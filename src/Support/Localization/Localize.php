<?php

namespace Presspack\Framework\Support\Localization;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;

class Localize
{
    protected $request;
    public $supportedLocales;

    public function __construct()
    {
        $this->request = app()['request'];
        $this->supportedLocales = $this->getSupportedLocales();
    }

    public function getSupportedLocales()
    {
        return null === config('presspack.supported_locales')
            ? DB::table('icl_languages')->where('active', '1')->pluck('code')->all()
            : config('presspack.supported_locales');
    }

    public function prefixFromRequest(int $segment = 0): ?string
    {
        $url = $this->request->getUri();

        return $this->getLocaleFromUrl($url, $segment);
    }

    public function getLocaleFromUrl(string $url, int $segment = 0): ?string
    {
        return $this->parseUrl($url, $segment)['locale'];
    }

    protected function parseUrl(string $url, int $segment = 0)
    {
        $parsedUrl = parse_url($url);
        $parsedUrl['segments'] = array_values(array_filter(explode('/', $parsedUrl['path']), 'strlen'));
        $localeCandidate = array_get($parsedUrl['segments'], $segment, false);
        $parsedUrl['locale'] = \in_array($localeCandidate, $this->supportedLocales, true) ? $localeCandidate : null;
        $parsedUrl['query'] = array_get($parsedUrl, 'query', false);
        $parsedUrl['fragment'] = array_get($parsedUrl, 'fragment', false);
        unset($parsedUrl['path']);

        return $parsedUrl;
    }

    public function isValidLocale(string $locale): bool
    {
        return \in_array($locale, $this->supportedLocales, true);
    }

    public function url(string $url, string $locale = null, int $segment = 0): string
    {
        $locale = $locale ?: App::getLocale();
        $cleanUrl = $this->cleanUrl($url, $segment);
        $parsedUrl = $this->parseUrl($cleanUrl, $segment);
        // Check if there are enough segments, if not return url unchanged:
        if (\count($parsedUrl['segments']) >= $segment) {
            array_splice($parsedUrl['segments'], $segment, 0, $locale);
        }

        return url($this->pathFromParsedUrl($parsedUrl));
    }

    /**
     * Removes the domain and locale (if present) of a given url.
     *
     * Ex:
     * http://www.domain.com/locale/random => /random,
     * https://www.domain.com/random => /random, http://domain.com/random?param=value => /random?param=value.
     */
    public function cleanUrl(string $url, int $segment = 0): string
    {
        $parsedUrl = $this->parseUrl($url, $segment);
        // Remove locale from segments:
        if ($parsedUrl['locale']) {
            unset($parsedUrl['segments'][$segment]);
            $parsedUrl['locale'] = false;
        }

        return $this->pathFromParsedUrl($parsedUrl);
    }

    /**
     * Returns the uri for the given parsed url based on its segments, query and fragment.
     */
    protected function pathFromParsedUrl(array $parsedUrl): string
    {
        $path = '/'.implode('/', $parsedUrl['segments']);
        if ($parsedUrl['query']) {
            $path .= "?{$parsedUrl['query']}";
        }
        if ($parsedUrl['fragment']) {
            $path .= "#{$parsedUrl['fragment']}";
        }

        return $path;
    }
}
