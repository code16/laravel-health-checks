<?php

namespace Code16\LaravelHealthChecks\Checks;

use Code16\LaravelHealthChecks\Checks\Traits\HasFileSizeParsing;
use Code16\LaravelHealthChecks\Support\PhpIni;
use Spatie\Health\Checks\Check;
use Spatie\Health\Checks\Result;

class PhpUploadConfigCheck extends Check {
    use HasFileSizeParsing;

    protected int $maxUploadSizeInMb = 32;

    protected ?int $postMaxSizeInMb = null;

    protected bool $allowGreaterValue = false;

    protected PhpIni $phpIni;

    public function __construct()
    {
        parent::__construct();
        $this->phpIni = new PhpIni();
    }

    public function run(): Result
    {
        $uploadResult = $this->checkUploadMaxSize();
        $messages = [$uploadResult->notificationMessage];
        $failed = $uploadResult->status->value === 'failed';
        $meta = $uploadResult->meta;

        if ($this->postMaxSizeInMb !== null) {
            $postResult = $this->checkPostMaxSize();
            $messages[] = $postResult->notificationMessage;
            if ($postResult->status->value === 'failed') {
                $failed = true;
            }
            $meta = array_merge($meta, $postResult->meta);
        }

        $result = Result::make();

        if ($failed) {
            $result->failed(implode('; ', $messages));
        } else {
            $result->ok(implode('; ', $messages));
        }

        return $result->meta($meta);
    }

    protected function checkUploadMaxSize() {
        $uploadMaxFileSize = $this->phpIni->get('upload_max_filesize');
        $parsedUploadMaxFileSize = $this->parseFileSizeToMb($uploadMaxFileSize);

        if($parsedUploadMaxFileSize >= $this->maxUploadSizeInMb) {
            if (!$this->allowGreaterValue && $parsedUploadMaxFileSize > $this->maxUploadSizeInMb) {
                return Result::make()
                    ->appendMeta(['upload_max_filesize' => $uploadMaxFileSize])
                    ->failed("upload_max_filesize is too high: {$uploadMaxFileSize}");
            }

            return Result::make()
                ->appendMeta([
                    'upload_max_filesize' => $uploadMaxFileSize,
                    'is_upload_greater_than_expected' => $parsedUploadMaxFileSize > $this->maxUploadSizeInMb
                ])
                ->ok("upload_max_filesize: {$uploadMaxFileSize}");

        }

        return Result::make()
            ->appendMeta(['upload_max_filesize' => $uploadMaxFileSize])
            ->failed("upload_max_filesize is incorrect: {$uploadMaxFileSize} (should be {$this->maxUploadSizeInMb}M)");
    }


    protected function checkPostMaxSize() {
        $postMaxSize = $this->phpIni->get('post_max_size');
        $parsedPostMaxSize = $this->parseFileSizeToMb($postMaxSize);

        if($parsedPostMaxSize >= $this->postMaxSizeInMb) {
            if (!$this->allowGreaterValue && $parsedPostMaxSize > $this->postMaxSizeInMb) {
                return Result::make()
                    ->appendMeta(['post_max_size' => $postMaxSize])
                    ->failed("post_max_size is too high: {$postMaxSize}");
            }

            return Result::make()
                ->appendMeta([
                    'post_max_size' => $postMaxSize,
                    'is_post_greater_than_expected' => $parsedPostMaxSize > $this->postMaxSizeInMb
                ])
                ->ok("post_max_size: {$postMaxSize}");

        }

        return Result::make()
            ->appendMeta(['post_max_size' => $postMaxSize])
            ->failed("post_max_size is incorrect: {$postMaxSize} (should be {$this->postMaxSizeInMb}M)");
    }

    public function setMaxUploadSizeInMb(int $maxUploadSizeInMb): self
    {
        $this->maxUploadSizeInMb = $maxUploadSizeInMb;
        return $this;
    }

    public function setPostMaxSizeInMb(int $postMaxSizeInMb): self
    {
        $this->postMaxSizeInMb = $postMaxSizeInMb;
        return $this;
    }

    public function allowGreaterValue(): self
    {
        $this->allowGreaterValue = true;
        return $this;
    }


}
