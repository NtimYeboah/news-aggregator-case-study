<?php

namespace App\News\Transformers;

use App\News\Transformer;

class NewYorkTimes extends Transformer
{
    /**
     * Response status
     *
     * @var string|null
     */
    public ?string $status;

    /**
     * Returned list of news from source.
     *
     * @var array
     */
    public array $news;

    public function __construct(array $response)
    {
        $this->status = $response['status'];
        $this->news = $response['response']['docs'];
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
            'title' => $data['headline']['main'],
            'description' => $data['lead_paragraph'] ?? $data['abstract'],
            'content' => $data['content'] ?? $data['snippet'],
            'url' => $data['web_url'],
            'image_url' => $data['multimedia'][0]['url'] ?? $data['multimedia']['default']['url'],
            'published_at' => $data['pub_date'],
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
        $person = [];
        
        if (isset($data['byline']['person'])) {
            $person = $data['byline']['person'];
        }

        if (count($person) > 0) {
            return $person[0]['firstname'] . ' ' . $person[0]['lastname'];
        }

        return null;
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
            'name' => $data['news_desk'] ?? $data['section_name'] ?? $data['subsection_name'],
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
            'name' => 'New York Times',
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
