<?php

namespace App\Actions;

use App\Models\Author;
use App\Models\Category;
use App\Models\News;
use App\Models\Source;
use Illuminate\Support\Str;

class SaveNewsHandler
{   
    public array $source;
    public array $category;
    public ?string $author;
    public array $article;

    public function __construct(array $data)
    {
        $this->source = $data['source'];
        $this->category = $data['category'];
        $this->author = $data['author'];
        $this->article = $data['article'];
    }

    /**
     * Save news.
     *
     * @return void
     */
    public function execute()
    {
        $source = $this->saveSource();
        $author = $this->saveAuthor();
        $category = $this->saveCategory();
        
        News::saveOne($source, $author, $category, $this->article);
    }

    /**
     * Save a new source.
     *
     * @return Source
     */
    public function saveSource()
    {
        $source = $this->source['name'] ??
            Source::firstOrCreate(
                ['slug' => 'unknown'],
                ['name' => 'Unknown']
            );

        if (!$source instanceof Source) {
            $source = Source::create([
                'name' => $source,
                'slug' => Str::slug($source),
            ]);
        }

        return $source;
    }

    /**
     * Save a new category.
     *
     * @return Category
     */
    public function saveCategory()
    {
        $category = $this->category['name'] ??
            Category::firstOrCreate(
                ['slug' => 'unknown'],
                ['name' => 'Unknown']
            );
    

        if (!$category instanceof Category) {
            $category = Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
            ]);
        }

        return $category;
    }

    /**
     * Save a new author.
     *
     * @return Author
     */
    public function saveAuthor()
    {
        $author = $this->author ??
            Author::firstOrCreate(
                ['name' => 'Unknown']
            );

        if (!$author instanceof Author) {
            $author = Author::create([
                'name' => $this->author,
            ]);
        }

        return $author;
    }
}
