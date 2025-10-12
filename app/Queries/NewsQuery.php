<?php

namespace App\Queries;

use App\Models\Author;
use App\Models\Category;
use App\Models\News;
use App\Models\Source;
use Illuminate\Support\Arr;

class NewsQuery
{
    /**
     * The search term.
     *
     * @var string|null
     */
    public ?string $term;
    
    /**
     * The date to retrieve news from.
     *
     * @var string|null
     */
    public ?string $dateFrom;

    /**
     * The date to retrieve news to.
     *
     * @var string|null
     */
    public ?string $dateTo;

    /**
     * The sources of the news.
     *
     * @var string|null
     */
    public ?string $sources;

    /**
     * The authors of the news.
     *
     * @var string|null
     */
    public ?string $authors;

    /**
     * The categories of the news.
     *
     * @var string|null
     */
    public ?string $categories;

    /**
     * The number of news to retrieve.
     *
     * @var integer|null
     */
    public ?int $perPage;
    
    public const PER_PAGE = 50;

    /**
     * Constructor.
     *
     * @param array $queryParameters
     */
    public function __construct(array $queryParameters)
    {
        $this->term = Arr::get($queryParameters, 'q');
        $this->dateFrom = Arr::get($queryParameters, 'from');
        $this->dateTo = Arr::get($queryParameters, 'to');
        $this->sources = Arr::get($queryParameters, 'sources');
        $this->authors = Arr::get($queryParameters, 'authors');
        $this->categories = Arr::get($queryParameters, 'categories');
        $this->perPage = Arr::get($queryParameters, 'per_page');
    }

    /**
     * Run query
     */
    public function run()
    {
        return News::with(['source', 'category', 'author'])
            ->when($this->sources, function ($query) {
                $query->whereIn('source_id', Source::query()->whereIn('slug', explode(',',$this->sources))->select('id'));
            })
            ->when($this->authors, function ($query) {
                $query->whereIn('author_id', Author::query()->whereIn('name', explode(',', $this->authors))->select('id'));
            })
            ->when($this->categories, function ($query) {
                $query->whereIn('category_id', Category::query()->whereIn('slug', explode(',', $this->categories))->select('id'));
            })
            ->when($this->term, function ($query) {
                $query->where('title', 'LIKE', '%' . $this->term . '%')
                    ->orWhere('content', 'LIKE', '%' . $this->term . '%');
            })
            ->when($this->dateFrom, function ($query) {
                $query->where('published_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->where('published_at', '<=', $this->dateTo);
            })
            ->select('news.*')
            ->paginate($this->perPage ?? self::PER_PAGE);
    }
}
