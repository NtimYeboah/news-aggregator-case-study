# News Aggregator

Innoscripta case study - Backend Developer

This application aggregates news from NewsAPI, The Guardian and The New York Times and saves them locally in the database.

It provides an API endpoint for the frontend application to interact with the backend. This endpoint
allows the frontend to retrieve articles based on search queries, filtering criteria (date, category, source), and user preferences (selected sources, categories, authors).

## Setup

### Environment Variables
Provide the values for the environment variables for these news sources. Get the API keys from the news sources and set them in `.env` file.

Set the `NEWS_RETRIEVAL_INTERVAL_MINUTES` environment variable to define how often (in minutes) news are fetched from the sources.

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



## Frontend interacting with the backend to retrieve news
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
