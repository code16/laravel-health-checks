<?php

namespace Code16\LaravelHealthChecks\Checks\Traits;

use InvalidArgumentException;

trait HasFileSizeParsing
{
    protected function parseFileSizeToMb(string $size): float|int
    {
        $size = trim($size);

        if (ctype_digit($size)) {
            return (int) $size;
        }

        if (!preg_match('/^([\d.]+)\s*(b|k|kb|m|mb|g|gb|t|tb)?$/i', $size, $matches)) {
            throw new InvalidArgumentException("Invalid size value: {$size}");
        }

        $number = (float) $matches[1];
        $unit  = strtolower($matches[2] ?? 'b');

        $multipliers = [
            'b'  => 1 / 1024 / 1024,
            'k'  => 1 / 1024,
            'kb' => 1 / 1024,
            'm'  => 1,
            'mb' => 1,
            'g'  => 1024,
            'gb' => 1024,
            't'  => 1024 * 1024,
            'tb' => 1024 * 1024,
        ];

        return (int) round($number * $multipliers[$unit]);
    }
}
