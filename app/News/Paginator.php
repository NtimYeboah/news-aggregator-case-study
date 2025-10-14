<?php

namespace App\News;

use Illuminate\Support\Str;

abstract class Paginator
{
    protected $response;

    /**
     * Instantiate response.
     *
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * Get page total.
     *
     * @return int
     */
    public abstract function getPageTotal(): int;
}
