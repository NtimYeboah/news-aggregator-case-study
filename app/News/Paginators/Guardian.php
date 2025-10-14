<?php

namespace App\News\Paginators;

use App\News\Paginator;

class Guardian extends Paginator
{
    /**
     * Instantiate response.
     *
     * @param array $response
     */
    public function __construct(array $response)
    {
        parent::__construct($response);
    }

    /**
     * Get page total.
     *
     * @return int
     */
    public function getPageTotal(): int
    {
        return (int) $this->response['response']['pages'];
    }
}
