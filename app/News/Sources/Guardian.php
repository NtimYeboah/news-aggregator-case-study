<?php

namespace App\News\Sources;

use App\News\Source;
use App\News\Paginators\Guardian as GuardianPaginator;
use App\News\Transformers\Guardian as GuardianTransformer;

class Guardian extends Source
{
    /**
     * Get full qualified url for source.
     *
     * @return string
     */
    public function url(): string
    {
        $url = "{$this->baseUrl()}?api-key={$this->apiKey()}";

        if ($searchTerm = $this->queryParameters->searchTerm()) {
            $url = $url . "&q={$searchTerm}";
        }

        if ($retrieveFrom = $this->queryParameters->retrieveFrom()) {
            $url = $url . "&from-date={$retrieveFrom->toDateString()}";
        }

        if ($retrieveTo = $this->queryParameters->retrieveTo()) {
            $url = $url . "&to-date={$retrieveTo->toDateString()}";
        }

        if ($page = $this->queryParameters->page()) {
            $url = $url . "&page={$page}";
        }

        return $url;
    }

    /**
     * Transform article to consistent format.
     *
     * @param array $body
     * @return void
     */
    public function transform(array $body): void
    {
        $transformer = new GuardianTransformer($body);

        $transformer->process();
    }

    /**
     * Get the total articles for an event.
     *
     * @param array $body
     * @return integer
     */
    public function getPageTotal(array $body): int
    {
        $paginator = new GuardianPaginator($body);

        return $paginator->getPageTotal();
    }
}
