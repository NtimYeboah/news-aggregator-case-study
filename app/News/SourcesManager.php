<?php

namespace App\News;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SourcesManager
{
    /**
     * Configures sources
     *
     * @var array
     */
    public array $config;

    public function __construct()
    {
        $this->config = config('services.news');
    }

    /**
     * Get configured sources.
     *
     * @return Collection
     */
    public function get()
    {
        $sources = collect();

        foreach ($this->config['sources'] as $sourceKey => $config) {
            $sourceClass = '\\App\\News\\Sources\\'. Str::studly($sourceKey);

            $sources->push(new $sourceClass($config));
        }

        return $sources;
    }

    /**
     * Get retrieval interval value.
     *
     * @return string
     */
    public function retrievalInterval()
    {
        return $this->config['retrieval_interval_minutes'];
    }
}
