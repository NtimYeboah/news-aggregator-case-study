<?php

namespace Tests\Feature;

use App\Actions\NewsRetrievalHandler;
use App\News\SourcesManager;
use App\Jobs\GetNews;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class NewsRetrievalHandlerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_news_from_configured_sources(): void
    {
        Queue::fake();

        Config::set('services.news.sources.news_api.api_key', '123abc');
        Config::set('services.news.sources.news_api.base_url', 'https://newsapi.org');

        Config::set('services.news.sources.new_york_times.api_key', '123abc');
        Config::set('services.news.sources.new_york_times.base_url', 'https://newyorktimes-api.org');

        Config::set('services.news.sources.guardian.api_key', '123abc');
        Config::set('services.news.sources.guardian.base_url', 'https://api.guardian.org');

        $newsRetrievalHander = new NewsRetrievalHandler(new SourcesManager());

        $newsRetrievalHander->execute();

        Queue::assertPushed(GetNews::class, 3);
    }
}
