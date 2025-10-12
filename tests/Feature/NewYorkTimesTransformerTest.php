<?php

namespace Tests\Feature;

use App\Models\News;
use App\News\Transformers\NewYorkTimes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewYorkTimesTransformerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_transform_and_save_new_york_times_articles(): void
    {
        $this->assertEquals(0, News::count());

        $responseMock = [
            'status' => 'OK',
            'copyright' => 'Copyright (c) 2024 The New York Times Company. All Rights Reserved.',
            'response' => [
                'docs' => [
                    [
                        'abstract' => 'Tired of real politics? Try the silver screen instead.',
                        'web_url' => 'https://www.nytimes.com/2024/12/20/us/politics/political-movies.html',
                        'snippet' => 'Tired of real politics? Try the silver screen instead.',
                        'lead_paragraph' => 'As 2024 draws to a close, you might be tired of watching politicians act as if they are the stars of an epic film.',
                        'source' => 'The New York Times',
                        'multimedia' => [
                            [
                                'rank' => 0,
                                'subtype' => 'xlarge',
                                'url' => 'images/2024/12/20/multimedia/20-pol-on-politics-newsletter-movies-topart-tgjz/20-pol-on-politics-newsletter-movies-topart-tgjz-articleLarge.jpg',
                            ]
                        ],
                        'headline' => [
                            'main' => 'The Best Movies About Politics (According to You)',
                            'kicker' => NULL,
                        ],
                        'pub_date' => '2024-12-21T01:05:31+0000',
                        'document_type' => 'article',
                        'news_desk' => 'Politics',
                        'section_name' => 'U.S.',
                        'subsection_name' => 'Politics',
                        'byline' => [
                            'original' => 'By Jess Bidgood',
                            'person' => [
                                [
                                    'firstname' => 'Jess',
                                    'middlename' => NULL,
                                    'lastname' => 'Bidgood',
                                    'qualifier' => NULL,
                                ]
                            ]
                        ],
                        'type_of_material' => 'News',
                        '_id' => 'nyt://article/a758c131-0f8a-5679-9dde-b4ee8bc275ad',
                        'word_count' => 1147,
                        'uri' => 'nyt://article/a758c131-0f8a-5679-9dde-b4ee8bc275ad',
                    ]
                ]
            ]
        ];

        (new NewYorkTimes($responseMock))->process();

        $this->assertEquals(1, News::count());
    }
}
