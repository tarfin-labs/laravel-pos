<?php

namespace TarfinLabs\LaravelPos;

use Illuminate\Support\Facades\Facade;

/**
 * @see \TarfinLabs\LaravelPos\Skeleton\SkeletonClass
 */
class LaravelPosFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-pos';
    }
}
