<?php

namespace Code16\LaravelHealthChecks\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Code16\LaravelHealthChecks\Checks\PhpUploadConfigCheck
 */
class LaravelHealthChecks extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Code16\LaravelHealthChecks\Checks\PhpUploadConfigCheck::class;
    }
}
