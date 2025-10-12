<?php

namespace Tests\Feature;

use App\Models\News;
use App\News\Transformers\Guardian;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuardianTransformerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_transform_and_save_guardian_articles(): void
    {
        $this->assertEquals(0, News::count());

        $responseMock = [
            'response' => [
                'status' => 'ok',
                'userTier' => 'developer',
                'total' => 1,
                'startIndex' => 1,
                'pageSize' => 1,
                'currentPage' => 1,
                'pages' => 1,
                'orderBy' => 'newest',
                'results' => [
                    [
                        'id' => 'football/2024/dec/22/amorim-says-manchester-united-were-nervous-after-rout-by-bournemouth',
                        'type' => 'article',
                        'sectionId' => 'football',
                        'sectionName' => 'Football',
                        'webPublicationDate' => '2024-12-22T18:57:54Z',
                        'webTitle' => 'Amorim says Manchester United were nervous after rout by Bournemouth',
                        'webUrl' => 'https://www.theguardian.com/football/2024/dec/22/amorim-says-manchester-united-were-nervous-after-rout-by-bournemouth',
                        'apiUrl' => 'https://content.guardianapis.com/football/2024/dec/22/amorim-says-manchester-united-were-nervous-after-rout-by-bournemouth',
                        'isHosted' => false,
                        'pillarId' => 'pillar/sport',
                        'pillarName' => 'Sport',
                    ]
                ]
            ]
        ];

        (new Guardian($responseMock))->process();

        $this->assertEquals(1, News::count());
    }
}
