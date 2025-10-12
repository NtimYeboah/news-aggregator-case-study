<?php

namespace App\Jobs;

use App\Models\NewsRetrievalAttempt;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class GetNews implements ShouldQueue
{
    use Queueable;

    /**
     * Number of request retries.
     */
    public const RETRY = 3;

    /**
     * Time to wait before retry in milliseconds.
     */
    public const RETRY_WAIT_TIME = 100;

    /**
     * Create a new job instance.
     */
    public function __construct(public NewsRetrievalAttempt $retrievalAttempt)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->retrievalAttempt->setStarted();
        
        $response = Http::retry(self::RETRY, self::RETRY_WAIT_TIME)
            ->get($this->retrievalAttempt->getUrl());

        $response->throwIf($response->failed());

        $this->retrievalAttempt->setCompleted($response);

        $sourceTransformer = 'App\\News\\Transformers\\'. Str::studly($this->retrievalAttempt->source);

        TransformResponse::dispatch($response->json(), $sourceTransformer);
    }

    /**
     * Called when job fails.
     *
     * @param Throwable|null $exception
     * @return void
     */
    public function failed(?Throwable $exception): void
    {
        $this->retrievalAttempt->setFailed();
    }
}

