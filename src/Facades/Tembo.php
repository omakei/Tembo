<?php

namespace Omakei\Tembo\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Omakei\Tembo\Tembo
 */
class Tembo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Omakei\Tembo\Tembo::class;
    }
}
