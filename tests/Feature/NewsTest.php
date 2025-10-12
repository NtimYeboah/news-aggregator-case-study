<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Category;
use App\Models\News;
use App\Models\Source;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_news_without_filter(): void
    {
        News::factory()
            ->count(3)
            ->create();

        $response = $this->getJson('api/news')
            ->assertStatus(Response::HTTP_OK);

        $this->assertCount(3, $response->json()['data']);
    }

    public function test_can_get_news_filtered_by_a_term(): void
    {
        News::factory()->create();
        News::factory()->create(['title' => 'Ntim']);
        News::factory()->create(['content' => 'Ntim']);

        $response = $this->getJson('api/news?q=Ntim')
            ->assertStatus(Response::HTTP_OK);

        $this->assertCount(2, $response->json()['data']);
    }

    public function test_can_get_news_filtered_by_date_from(): void
    {
        $fromDate = '2024-12-21 16:21:00';

        News::factory()
            ->count(3)
            ->state(new Sequence(
                ['published_at' => '2024-12-21 16:20:00'],
                ['published_at' => $fromDate],
                ['published_at' => '2024-12-21 16:22:00']
            ))
            ->create();

        $response = $this->getJson('api/news?from='.$fromDate)
            ->assertStatus(Response::HTTP_OK);

        $this->assertCount(2, $response->json()['data']);
    }

    public function test_can_get_news_filtered_by_date_to(): void
    {
        $toDate = '2024-12-21 16:21:00';

        News::factory()
            ->count(3)
            ->state(new Sequence(
                ['published_at' => '2024-12-21 16:20:00'],
                ['published_at' => $toDate],
                ['published_at' => '2024-12-21 16:22:00']
            ))
            ->create();

        $response = $this->getJson('api/news?from='.$toDate)
            ->assertStatus(Response::HTTP_OK);

        $this->assertCount(2, $response->json()['data']);
    }

    public function test_can_get_news_filtered_between_dates()
    {
        $fromDate = '2024-12-21 16:21:00';
        $toDate = '2024-12-21 16:23:00';

        News::factory()
            ->count(5)
            ->state(new Sequence(
                ['published_at' => '2024-12-21 16:20:00'],
                ['published_at' => $fromDate],
                ['published_at' => '2024-12-21 16:22:00'],
                ['published_at' => $toDate],
                ['published_at' => '2024-12-21 16:24:00'],
            ))
            ->create();

        $response = $this->getJson('api/news?from='.$fromDate.'&to='.$toDate)
            ->assertStatus(Response::HTTP_OK);

        $this->assertCount(3, $response->json()['data']);
    }

    public function test_can_get_news_filtered_by_sources()
    {
        News::factory()
            ->for(Source::factory()->state([
                'slug' => 'bbc-news',
            ]))
            ->create();

        News::factory()
            ->for(Source::factory()->state([
                'slug' => 'fox-news',
            ]))
            ->create();

        News::factory()
            ->for(Source::factory()->state([
                'slug' => 'cnn',
            ]))
            ->create();

        $response = $this->getJson('api/news?sources=bbc-news,fox-news')
            ->assertStatus(Response::HTTP_OK);

        $this->assertCount(2, $response->json()['data']);
    }

    public function test_can_get_news_filtered_by_category()
    {
        News::factory()
            ->for(Category::factory()->state([
                'slug' => 'football',
            ]))
            ->create();

        News::factory()
            ->for(Category::factory()->state([
                'slug' => 'politics',
            ]))
            ->create();

        News::factory()
            ->for(Category::factory()->state([
                'slug' => 'technology',
            ]))
            ->create();

        $response = $this->getJson('api/news?categories=football,technology')
            ->assertStatus(Response::HTTP_OK);

        $this->assertCount(2, $response->json()['data']);
    }

    public function test_can_get_news_filtered_by_author()
    {
        News::factory()
            ->for(Author::factory()->state([
                'name' => 'Pep',
            ]))
            ->create();

        News::factory()
            ->for(Author::factory()->state([
                'name' => 'Maudlina Brown',
            ]))
            ->create();

        News::factory()
            ->for(Author::factory()->state([
                'name' => 'Owen Jon',
            ]))
            ->create();

        $response = $this->getJson('api/news?authors=Pep,Maudlina Brown')
            ->assertStatus(Response::HTTP_OK);

        $this->assertCount(2, $response->json()['data']);
    }
}
