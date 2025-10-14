<?php

namespace App\Jobs;

use App\Enums\NewsRetrievalAttemptStatus;
use App\Enums\NewsRetrievalEventStatus;
use App\Models\NewsRetrievalAttempt;
use App\Models\NewsRetrievalEvent;
use App\News\Source;
use App\News\SourcesManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\RequestException;
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
    public function __construct(public SourcesManager $sources)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->sources->get() as $source) {
            $this->getNewsFromSource($source);
        }
    }

    /**
     * Get news from source.
     *
     * @param Source $source
     * @return void
     */
    protected function getNewsFromSource(Source $source): void
    {
        $latestNewsRetrievalEvent = $this->getLatestEvent($source);

        $iterations = 1;
        $pages = 1;

        do {
            $this->setSourceQueryParameters($source, $latestNewsRetrievalEvent, $iterations);

            $retrievalAttempt = $this->createRetrievalAttempt($source, $latestNewsRetrievalEvent);

            try {
                $response = Http::retry(self::RETRY, self::RETRY_WAIT_TIME)
                    ->get($retrievalAttempt->getUrl())
                    ->throw();
            } catch (RequestException $exception) {
                if ($exception->getCode() === 429) {
                    logger('Request was rate limited:::' . $source->url());
                    break;
                }

                throw $exception;
            }

            $retrievalAttempt->setCompleted($response);

            $pages = $source->getPageTotal($response->json());

            if ($pages > 1) {
                $iterations++;
            }

            $source->transform($response->json());
        } while ($iterations <= $pages);
    }

    /**
     * Get latest event that happened for source.
     *
     * @param Source $source
     * @return NewsRetrievalEvent $latestNewsRetrievalEvent
     */
    protected function getLatestEvent(Source $source): NewsRetrievalEvent
    {
        $latestNewsRetrievalEvent = NewsRetrievalEvent::for($source->name())->latest()->first();

        if (! $latestNewsRetrievalEvent || $latestNewsRetrievalEvent->successful()) {
            $latestNewsRetrievalEvent = NewsRetrievalEvent::create([
                'source' => $source->name(),
                'status' => NewsRetrievalEventStatus::STARTED->value,
                'started_at' => now()->subMinutes($this->sources->retrievalInterval()),
            ]);
        }

        return $latestNewsRetrievalEvent;
    }

    protected function createRetrievalAttempt(Source $source, NewsRetrievalEvent $newsRetrievalEvent): NewsRetrievalAttempt
    {
        return NewsRetrievalAttempt::create([
            'event_id' => $newsRetrievalEvent->getKey(),
            'retrieved_from' => $newsRetrievalEvent->started_at,
            'started_at' => now(),
            'status' => NewsRetrievalAttemptStatus::STARTED->value,
            'source' => $source->name(),
            'url' => $source->url(),
        ]);
    }

    /**
     * Set query parameters on source.
     *
     * @param Source $source
     * @param NewsRetrievalEvent $retrievalEvent
     * @return void
     */
    protected function setSourceQueryParameters(Source $source, NewsRetrievalEvent $retrievalEvent, int $page): void
    {
        $source->setQueryParameters([
            'retrieve_from' => $retrievalEvent->started_at,
            'page' => $page,
        ]);
    }
}
