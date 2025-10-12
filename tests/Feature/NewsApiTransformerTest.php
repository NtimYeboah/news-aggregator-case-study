<?php

namespace Tests\Feature;

use App\Models\News;
use App\News\Transformers\NewsApi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsApiTransformerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_transform_and_save_newapi_articles(): void
    {
        $this->assertEquals(0, News::count());

        $responseMock = [
            'status' => 'ok',
            'totalResults' => 638,
            'articles' => [
                [
                    'source' => [
                        'id' => NULL,
                        'name' => 'BBC News',
                    ],
                    'author' => 'BBC Sport',
                    'title' => 'England 1966 World Cup squad member Eastham dies at 88',
                    'description' => 'George Eastham, a member of England\'s 1966 World Cup-winning squad, dies aged 88.',
                    'url' => 'https://www.bbc.com/sport/football/articles/cn4xjdl7yl8o',
                    'urlToImage' => 'https://ichef.bbci.co.uk/ace/branded_sport/1200/cpsprodpb/ce04/live/a84a8050-bf6d-11ef-84c1-3746882e82c2.jpg',
                    'publishedAt' => '2024-12-21T08:06:35Z',
                    'content' => 'George Eastham, a member of England\'s 1966 World Cup-winning squad, has died aged 88.
                    The forward made 19 international appearances and, while he was part of manager Sir Alf Ramsey\'s squad at the',
                ],
                [
                    'source' => [
                        'id' => 'espn',
                        'name' => 'ESPN',
                    ],
                    'author' => 'Brooke Pryor',
                    'title' => 'How Steelers great Ryan Shazier recovered from injury to become a coach',
                    'description' => 'After trying to spend some time away from football, the former linebacker is part of Mike Tomlin\'s staff.',
                    'url' => 'https://www.espn.com/nfl/story/_/id/43063081/pittsburgh-steelers-ryan-shazier-linebacker-coaching-retired',
                    'urlToImage' => 'https://a1.espncdn.com/combiner/i?img=%2Fphoto%2F2024%2F1221%2Fr1430392_1296x729_16%2D9.jpg',
                    'publishedAt' => '2024-12-21T12:20:44Z',
                    'content' => 'PITTSBURGH -- Wearing a black puffer jacket and a black Pittsburgh Steelers-logoed beanie punctuated with a thick yellow stripe.',
                ],
            ],
        ];

        (new NewsApi($responseMock))->process();

        $this->assertEquals(2, News::count());
    }
}
