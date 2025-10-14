<?php

namespace App\News\Sources;

use App\News\Source;
use App\ValueObjects\QueryParameters;

class NewYorkTimes extends Source
{
    /**
     * Set query parameters for source.
     *
     * @param array $parameters
     * @return void
     */
    public function setQueryParameters(array $parameters): void
    {
        $defaultParameters = [
            'retrieve_from' => '',
            'retrieve_to' => '',
            'search_term' => 'politics',
            'sort_key' => '',
            'page_size' => '',
            'page' => '',
        ];

        $this->queryParameters = QueryParameters::fromArray(array_merge($defaultParameters, $parameters));
    }

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
            $url = $url . "&begin_date={$retrieveFrom->format('Ymd')}";
        }

        if ($retrieveTo = $this->queryParameters->retrieveTo()) {
            $url = $url . "&end_date={$retrieveTo->format('Ymd')}";
        }

        if ($pageSize = $this->queryParameters->pageSize()) {
            $url = $url . "&pageSize={$pageSize}";
        }

        if ($page = $this->queryParameters->page()) {
            $url = $url . "&page={$page}";
        }

        return $url;
    }
}
