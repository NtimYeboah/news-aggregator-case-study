<?php

namespace App\News\Transformers;

use App\News\Transformer;

class NewsApi extends Transformer
{
    public ?string $status;

    /**
     * Total number of results returned.
     *
     * @var int|null
     */
    public ?int $totalResults;

    /**
     * Returned list of news from source.
     *
     * @var array
     */
    public array $news;

    public function __construct(array $response)
    {
        $this->status = $response['status'];
        $this->totalResults = $response['totalResults'];
        $this->news = $response['articles'];
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
            'title' => $data['title'],
            'description' => $data['description'],
            'content' => $data['content'],
            'url' => $data['url'],
            'image_url' => $data['urlToImage'],
            'published_at' => $data['publishedAt'],
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
        return $data['author'] ?? null;
    }

    /**
     * Get transformed category.
     *
     * @param array $data
     * @return array
     */
    public function getCategory(array $data): array
    {
        return [];
    }

    /**
     * Get transformed source.
     *
     * @param array $data
     * @return array
     */
    public function getSource(array $data): array
    {
        return $data['source'];
    }

    /**
     * Determine whether news item is valid to be saved.
     *
     * @param array $data
     * @return boolean
     */
    public function isValid(array $data): bool
    {
        return $data['source']['name'] !== '[Removed]';
    }
}
