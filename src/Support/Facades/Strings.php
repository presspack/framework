<?php

namespace Presspack\Framework\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Strings extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'presspack/strings';
    }
}
