<?php

namespace Tests\Unit;

use App\News\SourcesManager;
use App\News\Sources\Guardian;
use App\News\Sources\NewsApi;
use App\News\Sources\NewYorkTimes;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class SourcesManagerTest extends TestCase
{
    public function test_can_create_news_sources_from_configuration(): void
    {
        Config::set('services.news.retrieval_interval_minutes', 1);

        Config::set('services.news.sources.news_api.api_key', '123abc');
        Config::set('services.news.sources.news_api.base_url', 'https://newsapi.org');

        Config::set('services.news.sources.new_york_times.api_key', '123abc');
        Config::set('services.news.sources.new_york_times.base_url', 'https://newyorktimes-api.org');

        Config::set('services.news.sources.guardian.api_key', '123abc');
        Config::set('services.news.sources.guardian.base_url', 'https://api.guardian.org');

        $sources = (new SourcesManager())->get();
    
        $this->assertCount(3, $sources);

        $this->assertInstanceOf(NewsApi::class, $sources->get(0));
        $this->assertInstanceOf(NewYorkTimes::class, $sources->get(1));
        $this->assertInstanceOf(Guardian::class, $sources->get(2));
    }
}
