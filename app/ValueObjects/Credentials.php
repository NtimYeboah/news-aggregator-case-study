<?php

namespace App\ValueObjects;

use RuntimeException;

class Credentials
{
    /**
     * Constructor.
     *
     * @param array $credentials
     */
    private function __construct(private array $credentials)
    {
        // Throw exception when api_key and endpoint keys doesn't exist in array
        if (!isset($credentials['api_key'])) {
            throw new RuntimeException('API Key cannot be empty.');
        }

        if (!isset($credentials['base_url'])) {
            throw new RuntimeException('Base url cannot be empty.');
        }
    }

    /**
     * Transform from array.
     *
     * @param array $credentials
     * @return self
     */
    public static function fromArray(array $credentials): self
    {
        return new self($credentials);
    }

    /**
     * Get the API Key based on the credentials.
     *
     * @return string
     */
    public function apiKey(): string
    {
        return $this->credentials['api_key'];
    }

    /**
     * Get endpoint based on the credentials.
     *
     * @return string
     */
    public function baseUrl(): string
    {
        return $this->credentials['base_url'];
    }
}
