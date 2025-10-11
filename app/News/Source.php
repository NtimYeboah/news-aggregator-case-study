<?php

namespace App\News;

use App\ValueObjects\Credentials;
use App\ValueObjects\QueryParameters;
use Illuminate\Support\Str;

abstract class Source
{
    /**
     * Source credentials
     *
     * @var Credentials
     */
    protected Credentials $credentials;

    /**
     * Query paramters.
     *
     * @var QueryParameters
     */
    protected QueryParameters $queryParameters;

    public function __construct(array $config)
    {
        $this->credentials = Credentials::fromArray($config);
    }

    /**
     * Get full qualified url for the source.
     *
     * @return string
     */
    public abstract function url(): string;

    /**
     * Get the name of the class.
     *
     * @return string
     */
    public function name(): string
    {
        return $this->key();
    }

    /**
     * Get configured base url for source.
     *
     * @return string
     */
    public function baseUrl(): string
    {
        return $this->credentials->baseUrl();
    }

    /**
     * Get configured API Key for the source.
     *
     * @return string
     */
    public function apiKey(): string
    {
        return $this->credentials->apiKey();
    }

    /**
     * Get the key name for the class.
     *
     * @return string
     */
    protected function key(): string
    {
        $qualifiedClassName = get_called_class();

        $names = collect(explode('\\', $qualifiedClassName));

        return Str::snake($names->last(), '-');
    }

    /**
     * Set query parameters.
     *
     * @param array $parameters
     * @return void
     */
    public function setQueryParameters(array $parameters): void
    {
        $defaultParameters = [
            'retrieve_from' => '',
            'retrieve_to' => '',
            'search_term' => '',
            'sort_key' => '',
            'page_size' => '',
            'page' => '',
        ];

        $this->queryParameters = QueryParameters::fromArray(array_merge($defaultParameters, $parameters));
    }
}
