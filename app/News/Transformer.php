<?php

namespace App\News;

use App\Actions\SaveNewsHandler;

abstract class Transformer
{
    /**
     * Returned list of news from source.
     *
     * @var array
     */
    public array $news;

    /**
     * Get transformed article.
     *
     * @param array $data
     * @return array
     */
    public abstract function getArticle(array $data): array;

    /**
     * Get transformed author's name.
     *
     * @param array $data
     * @return string|null
     */
    public abstract function getAuthor(array $data): string|null;

    /**
     * Get transformed category.
     *
     * @param array $data
     * @return array
     */
    public abstract function getCategory(array $data): array;

    /**
     * Get transformed source.
     *
     * @param array $data
     * @return array
     */
    public abstract function getSource(array $data): array;

    /**
     * Determine whether news item is valid to be saved.
     *
     * @param array $data
     * @return boolean
     */
    public abstract function isValid(array $data): bool;

    /**
     * Save news.
     *
     * @return void
     */
    public function process()
    {
        foreach ($this->news as $news) {
            if (! $this->isValid($news)) {
                continue;
            }

            $transformed = $this->transform($news);
            
            (new SaveNewsHandler($transformed))->execute();
        }
    }

    /**
     * Transform response to appropriate format to be saved.
     *
     * @param array $data
     * @return array
     */
    public function transform(array $data): array
    {
        return [
            'source' => $this->getSource($data),
            'category' => $this->getCategory($data),
            'author' => $this->getAuthor($data),
            'article' => $this->getArticle($data),
        ];
    }
}
