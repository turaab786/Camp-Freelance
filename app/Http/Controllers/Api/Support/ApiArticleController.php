<?php

namespace App\Http\Controllers\Api\Support;

use App\Http\Controllers\Controller;
use App\Http\Resources\Support\ArticleResource;
use App\Support\Article;
use Illuminate\Http\Request;

class ApiArticleController extends Controller
{
    public function getArticleById($article_id) {
        $article = Article::with('category')->where('id', $article_id)->first();
        return response()->json(['data' => new ArticleResource($article)], 200);
    }

}
