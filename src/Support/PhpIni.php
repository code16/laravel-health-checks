<?php

namespace Code16\LaravelHealthChecks\Support;

class PhpIni
{
    protected static array $fakeValues = [];

    public function get(string $key): string|false
    {
        return static::$fakeValues[$key] ?? ini_get($key);
    }

    public static function fake(array $values): void
    {
        static::$fakeValues = array_merge(static::$fakeValues, $values);
    }

    public static function reset(): void
    {
        static::$fakeValues = [];
    }
}
