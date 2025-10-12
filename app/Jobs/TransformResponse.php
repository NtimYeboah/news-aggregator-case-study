<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class TransformResponse implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public array $responseBody, public string $transformerClass)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        (new $this->transformerClass($this->responseBody))->process();
    }
}

