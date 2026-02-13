<?php

namespace Code16\LaravelHealthChecks\Tests;

use Code16\LaravelHealthChecks\Checks\PhpUploadConfigCheck;
use Code16\LaravelHealthChecks\Support\PhpIni;
use Spatie\Health\Enums\Status;

beforeEach(function () {
    PhpIni::reset();
});

it('checks only upload_max_filesize by default', function () {
    PhpIni::fake(['upload_max_filesize' => '100M']);

    $check = (new PhpUploadConfigCheck())->setMaxUploadSizeInMb(100);
    $result = $check->run();

    expect($result->status->value)->toBe('ok');
    expect($result->notificationMessage)->toBe('upload_max_filesize: 100M');
});

it('checks both upload_max_filesize and post_max_size when configured', function () {
    PhpIni::fake([
        'upload_max_filesize' => '100M',
        'post_max_size' => '8M',
    ]);

    $check = (new PhpUploadConfigCheck())
        ->setMaxUploadSizeInMb(100)
        ->setPostMaxSizeInMb(8);
    $result = $check->run();

    expect($result->status->value)->toBe('ok');
    expect($result->notificationMessage)->toBe('upload_max_filesize: 100M; post_max_size: 8M');
});

it('fails if one of them fails', function () {
    PhpIni::fake([
        'upload_max_filesize' => '100M',
        'post_max_size' => '8M',
    ]);

    $check = (new PhpUploadConfigCheck())
        ->setMaxUploadSizeInMb(100)
        ->setPostMaxSizeInMb(128); // This should fail as post_max_size is 8M
    $result = $check->run();

    expect($result->status->value)->toBe('failed');
    expect($result->notificationMessage)->toContain('upload_max_filesize: 100M');
    expect($result->notificationMessage)->toContain('post_max_size is incorrect: 8M (should be 128M)');
});
