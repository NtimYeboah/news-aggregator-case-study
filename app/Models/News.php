<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Arr;

class News extends Model
{
    use HasFactory;

    /**
     * Casts attributes.
     *
     * @var array
     */
    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Source relationship.
     *
     * @return BelongsTo
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class, 'source_id');
    }

    /**
     * Category relationship.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Author relationship.
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class, 'author_id');
    }

    /**
     * Save a new article as news.
     *
     * @param Source $source
     * @param Author $author
     * @param Category $category
     * @param array $details
     * @return void
     */
    public static function saveOne(Source $source, Author $author, Category $category, array $details)
    {
        $news = new self;

        $news->source_id = $source->getKey();
        $news->category_id = $category->getKey();
        $news->author_id = $author->getKey();
        $news->title = Arr::get($details, 'title');
        $news->description = Arr::get($details, 'description');
        $news->content = Arr::get($details, 'content');
        $news->url = Arr::get($details, 'url');
        $news->image_url = Arr::get($details, 'image_url');
        $news->api_url = Arr::get($details, 'api_url');
        $news->published_at = Arr::get($details, 'published_at');

        $news->save();
    }
}
