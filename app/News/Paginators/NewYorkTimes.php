<?php

namespace App\News\Paginators;

use App\News\Paginator;

class NewYorkTimes extends Paginator
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
        return $this->response['response']['metadata']['time'];
    }
}
