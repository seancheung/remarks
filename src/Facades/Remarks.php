<?php

namespace Panoscape\Remarks\Facades;

use Illuminate\Support\Facades\Facade;

class Remarks extends Facade
{
    /**
     * The name of the binding in the IoC container.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \Panoscape\Remarks\Remarks::class;
    }
}
