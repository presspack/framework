<?php

namespace Presspack\Framework\Support\Localization;

use Closure;
use Illuminate\Support\Facades\App;
use Presspack\Framework\Support\Facades\Localize;

class Middleware
{
    public function handle($request, Closure $next, $segment = 0)
    {
        // Ignores all non GET requests:
        if ('GET' !== $request->method()) {
            return $next($request);
        }

        $currentUrl = $request->getUri();
        $uriLocale = Localize::getLocaleFromUrl($currentUrl, $segment);
        $defaultLocale = config('presspack.default_locale');
        // If a locale was set in the url:
        if ($uriLocale) {
            // Set app locale
            App::setLocale($uriLocale);

            return $next($request);
        }

        // If no locale was set in the url, check the browser's locale:
        $browserLocale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);
        if (Localize::isValidLocale($browserLocale)) {
            return redirect()->to(Localize::url($currentUrl, $browserLocale, $segment));
        }

        // If not, redirect to the default locale:

        return redirect()->to(Localize::url($currentUrl, $defaultLocale, $segment));
    }
}
