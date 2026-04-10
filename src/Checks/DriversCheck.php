<?php

namespace Code16\LaravelHealthChecks\Checks;

use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class DriversCheck extends Check
{
    protected array $drivers = [
        'session' => 'redis',
        'cache' => 'redis',
        'queue' => 'redis',
    ];

    public function run(): Result
    {
        $actualDrivers = [
            'session' => $this->getSessionDriver(),
            'cache' => $this->getCacheDriver(),
            'queue' => $this->getQueueDriver(),
        ];

        $errors = collect($this->drivers)
            ->map(function ($expectedValue, $key) use ($actualDrivers) {
                $actualValue = $actualDrivers[$key] ?? null;

                return $actualValue !== $expectedValue
                    ? "$key: $actualValue (should be $expectedValue)"
                    : null;
            })
            ->filter();

        if ($errors->isNotEmpty()) {
            return Result::make()
                ->failed($errors->implode(', '))
                ->shortSummary('Drivers check failed')
                ->meta($actualDrivers);
        }

        return Result::make()
            ->ok()
            ->shortSummary('Drivers are correct')
            ->meta($actualDrivers);
    }

    public function cacheDriverIs(string $driver): self
    {
        $this->drivers['cache'] = $driver;

        return $this;
    }

    public function queueDriverIs(string $driver): self
    {
        $this->drivers['queue'] = $driver;

        return $this;
    }

    public function sessionDriverIs(string $driver): self
    {
        $this->drivers['session'] = $driver;

        return $this;
    }

    protected function getSessionDriver(): string
    {
        return config('session.driver');
    }

    protected function getCacheDriver(): string
    {
        return config('cache.default');
    }

    protected function getQueueDriver(): string
    {
        return config('queue.default');
    }
}
