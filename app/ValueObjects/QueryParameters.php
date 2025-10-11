<?php

namespace App\ValueObjects;

use Illuminate\Support\Carbon;

class QueryParameters
{
    private function __construct(private array $parameters)
    {
        
    }

    /**
     * Transform from array.
     *
     * @param array $parameters
     * @return self
     */
    public static function fromArray(array $parameters): self
    {
        return new self($parameters);
    }

    /**
     * Date to retrieve from.
     *
     * @return Carbon|null
     */
    public function retrieveFrom()
    {
        return $this->parameters['retrieve_from'] ?? null;
    }

    /**
     * Date to retrieve to.
     *
     * @return Carbon|null
     */
    public function retrieveTo()
    {
        return $this->parameters['retrieve_to'] ?? null;
    }

    /**
     * The search term.
     *
     * @return string|null
     */
    public function searchTerm(): string|null
    {
        return $this->parameters['search_term'] ?? null;
    }

    /**
     * The sort key.
     *
     * @return string|null
     */
    public function sortKey(): string|null
    {
        return $this->parameters['sort_key'] ?? null;
    }

    /**
     * Number of items to retrieve.
     *
     * @return string|null
     */
    public function pageSize(): string|null
    {
        return $this->parameters['page_size'] ?? null;
    }

    /**
     * The page for the request.
     *
     * @return string|null
     */
    public function page(): string|null
    {
        return $this->parameters['page'] ?? null;
    }
}
