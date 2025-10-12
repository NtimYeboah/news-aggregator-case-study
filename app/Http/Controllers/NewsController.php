<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsRequest;
use App\Http\Resources\NewsResource;
use App\Queries\NewsQuery;

class NewsController extends Controller
{
    public function index(NewsRequest $request)
    {
        $news = (new NewsQuery($request->validated()))->run();

        return NewsResource::collection($news);
    }
}
