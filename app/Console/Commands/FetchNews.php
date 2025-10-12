<?php

namespace App\Console\Commands;

use App\Actions\NewsRetrievalHandler;
use Illuminate\Console\Command;

class FetchNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch articles from news sources.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        app(NewsRetrievalHandler::class)->execute();
    }
}
