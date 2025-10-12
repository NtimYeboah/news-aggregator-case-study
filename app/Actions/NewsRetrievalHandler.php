<?php

namespace App\Actions;

use App\News\Source;
use App\News\SourcesManager;
use App\Enums\NewsRetrievalAttemptStatus;
use App\Enums\NewsRetrievalEventStatus;
use App\Jobs\GetNews;
use App\Models\NewsRetrievalAttempt;
use App\Models\NewsRetrievalEvent;

class NewsRetrievalHandler
{
    public function __construct(public SourcesManager $sources)
    {
        
    }

    /**
     * Retrieve news from source.
     *
     * @return void
     */
    public function execute(): void
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
    public function getNewsFromSource(Source $source): void
    {
        $latestNewsRetrievalEvent = $this->getLatestEvent($source);

        $this->setSourceQueryParameters($source, $latestNewsRetrievalEvent);

        $retrievalAttempt = NewsRetrievalAttempt::create([
            'event_id' => $latestNewsRetrievalEvent->getKey(),
            'retrieved_from' => $latestNewsRetrievalEvent->started_at,
            'source' => $source->name(),
            'status' => NewsRetrievalAttemptStatus::NOT_STARTED->value,
            'url' => $source->url(),
        ]);

        GetNews::dispatch($retrievalAttempt);
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

    /**
     * Set query parameters on source.
     *
     * @param Source $source
     * @param NewsRetrievalEvent $retrievalEvent
     * @return void
     */
    protected function setSourceQueryParameters(Source $source, NewsRetrievalEvent $retrievalEvent): void
    {
        $source->setQueryParameters([
            'retrieve_from' => $retrievalEvent->started_at,
        ]);
    }
}
