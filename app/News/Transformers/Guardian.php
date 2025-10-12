<?php

namespace App\News\Transformers;

use App\News\Transformer;

class Guardian extends Transformer
{
    /**
     * The status of the request.
     *
     * @var string|null
     */
    public ?string $status;

    /**
     * Total number of results returned.
     *
     * @var int|null
     */
    public ?int $totalResults;

    /**
     * Total number of pages returned
     *
     * @var int|null
     */
    public ?int $pages;

    /**
     * Current page for pagination.
     *
     * @var string|null
     */
    public ?string $currentPage;

    /**
     * Returned list of news from source.
     *
     * @var array
     */
    public array $news;

    public function __construct(array $response)
    {
        $this->status = $response['response']['status'];
        $this->totalResults = $response['response']['total'];
        $this->pages = $response['response']['pages'];
        $this->currentPage = $response['response']['currentPage'];
        $this->news = $response['response']['results'];
    }

    /**
     * Get transformed article.
     *
     * @param array $data
     * @return array
     */
    public function getArticle(array $data): array
    {
        return [
            'title' => $data['webTitle'],
            'description' => $data['description'] ?? null,
            'content' => $data['content'] ?? null,
            'url' => $data['webUrl'],
            'image_url' => $data['urlToImage'] ?? null,
            'api_url' => $data['apiUrl'] ?? null,
            'published_at' => $data['webPublicationDate'],
        ];
    }

    /**
     * Get transformed author's name.
     *
     * @param array $data
     * @return string|null
     */
    public function getAuthor(array $data): string|null
    {
        return 'Guardian';
    }

    /**
     * Get transformed category.
     *
     * @param array $data
     * @return array
     */
    public function getCategory(array $data): array
    {
        return [
            'name' => $data['sectionName'] ?? null,
        ];
    }

    /**
     * Get transformed source.
     *
     * @param array $data
     * @return array
     */
    public function getSource(array $data): array
    {
        return [
            'name' => 'Guardian',
        ];
    }

    /**
     * Determine whether news item is valid to be saved.
     *
     * @param array $data
     * @return boolean
     */
    public function isValid(array $data): bool
    {
        return true;
    }
}
