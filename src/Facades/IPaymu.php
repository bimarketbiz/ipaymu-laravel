<?php

namespace Marketbiz\IPaymuLaravel\Facades;

use Illuminate\Support\Facades\Facade;

class IPaymu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ipaymu';
    }
}