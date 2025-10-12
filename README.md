# News Aggregator

Innoscripta case study - Backend Developer

This application fetches news from NewsAPI, The Guardian and The New York Times and saves them locally in the database.

It provides an API endpoint for the frontend application to interact with the backend. This endpoint
allows the frontend to retrieve articles based on search queries, filtering criteria (date, category, source), and user preferences (selected sources, categories, authors).

## Setup

### Environment Variables
Provide the values for the environment variables for these news sources. Get the API keys from the news sources and set them in `.env` file.

Set the value for the `NEWS_RETRIEVAL_INTERVAL_MINUTES` environment variable. This is a number in minutes of intervals used to fetch the articles. 

```sh
NEWS_RETRIEVAL_INTERVAL_MINUTES=5

NEWSAPI_BASE_URL=https://newsapi.org/v2/everything
NEWSAPI_API_KEY=

GUARDIANAPI_BASE_URL=https://content.guardianapis.com/search
GUARDIANAPI_API_KEY=

NEWYORKTIMES_BASE_URL=https://api.nytimes.com/svc/search/v2/articlesearch.json
NEWYORKTIMES_API_KEY=
```

### Start the queue worker
Run the command below to start the queue worker.
```sh
php artisan queue:work
```

### Start the scheduler
Run the command below to start the schedule worker.
```sh
php artisan schedule:work
```



## Fetching news articles from backend
Use the `/api/news` endpoint to fetch news from the backend. This endpoint include parameters to filter the news results. You can filter by:
1. Source.
2. Category.
3. Dates.
4. Author.
5. Search term.
6. Total to return.

### Filter by source
Use the `sources` query parameter to filter news articles by source. This takes a list of sources separated by comma. For example, this url returns news articles from BBC News, CNN and Fox news.
```sh
/api/news?sources=bbc-news,cnn,fox-news
```

### Filter by category
Use the `categories` query parameter to filter news articles by category. This takes a list of categories separated by comma. For example, this url returns news articles from politics and football.
```sh
/api/news?categories=politics,football
```

### Filter by author
Use the `authors` query parameter to filter news articles by author. This takes a list of author names separated by comma. For example, this url returns news articles from Pep and Maudlina Brown.
```sh
/api/news?authors=Pep,Maudlina Brown
```

### Filter by dates
Use the `from` and `to` query parameters to filter news articles by publication date. This takes a date in the format `Y-m-d H:i:s`. For example, lets get the news between 10th October, 2025 and 12nd October, 2025.
```sh
/api/news?from=2025-10-10 16:20:00&to=2025-10-12 16:20:00
```

### Filter by search term
Use the `q` query parameter to search article using a term. This searches the articles title and contents.
```sh
/api/news?q=laravel
```

### Specify the number of news articles to return
You can specify the number of news articles to return by using the `per_page` query parameter. The results will be paginates if there are more articles to be fetched. The default total returned is `50`.
```sh
/api/news?per_page=100
```

## Add more news sources
This application has been built to make it easier to add more news sources without modifying the core functionality. To do this:

1. Add the API Key and the Base url of the source to the `.env` file.
2. Add a key to the list of sources in the config/services news field.
3. Add a Source class for your news source.
Your class should extend the `App\News\Source` abstract class and placed in the `app/News/Sources` directory. Your class should implement the `url()` method. This method should return a url specific for this source to retrieve news.

```php
<?php

namespace App\News\Sources;

use App\News\Source;

class BbcNews extends Source
{
    /**
     * Get full qualified url for source.
     *
     * @return string
     */
    public function url()
    {
        //
    }
}
```
4. Add a Transformer class to transform the data when news are fetched from the source. Your transformer class should extend the `App\News\Transformer` abstract class. Your transformer class should be placed in the `app/News/Transformers` directory.
Your transformer class should implement the following methods:
- `getArticle(array $data): array` - This method should transform an article to be saved in the database.
- `getAuthor(array $data): string` - This method should transform an author to be saved in the database.
- `getCategory(array $data): array` - This method should transform a category for the article to be saved in the database.
- `getSource(array $data): array` - This method should transform a source of the article to be saved in the database.
- `isValid(array $data): bool` - This method determines whether an articles should be transformed so it can be saved in the database.

```php
namespace App\News\Transformers;

use App\News\Transformer;

class BbcNews extends Transformer
{
    /**
     * Get transformed article.
     *
     * @param array $data
     * @return array
     */
    public function getArticle(array $data): array
    {
        //...
    }
    
    /**
     * Get transformed author name.
     *
     * @param array $data
     * @return string|null
     */
    public function getAuthor(array $data): string
    {
        //...
    }
    
    /**
     * Get transformed category.
     *
     * @param array $data
     * @return array
     */
    public function getCategory(array $data): array
    {
        //...
    }
    
    /**
     * Get transformed source.
     *
     * @param array $data
     * @return array
     */
    public function getSource(array $data): array
    {
        //...
    }
    
    /**
     * Determine whether news item is valid to be saved.
     *
     * @param array $data
     * @return boolean
     */
    public function isValid(array $data): bool
    {
        //...
    }
}
```
