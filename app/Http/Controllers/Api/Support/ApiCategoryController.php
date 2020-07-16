<?php

namespace App\Http\Controllers\Api\Support;

use App\Http\Controllers\Controller;
use App\Http\Resources\Support\CategoryResource;
use App\Support\Category;
use Illuminate\Http\Request;

class ApiCategoryController extends Controller
{
    public function getHomeCategories(Request $request) {
        $categories = [];

        $query = Category::query();

        if ( $request->segment(4) == 'seller' ) {
            $categories = $query->withCount('seller_articles')
                ->with('seller_articles')
                ->has('seller_articles')->take(6)->get()->map(function ($query) {
                    // to get only three articles
                    $query->setRelation('seller_articles', $query->seller_articles->take(3));
                    return $query;
                });
        } else {
            $categories = $query->withCount('buyer_articles')
                ->with('buyer_articles')
                ->has('buyer_articles')->take(6)->get()->map(function ($query) {
                    //to get only three articles
                    $query->setRelation('buyer_articles', $query->buyer_articles->take(3));
                    return $query;
                });
        }


        return response()->json(['data' => CategoryResource::collection($categories)], 200);
    }

    public function getAllCategories(Request $request) {
        $categories = [];

        $query = Category::query();

        if ( $request->segment(4) == 'seller' ) {
            $query = $query->withCount('seller_articles')
                ->with('seller_articles')
                ->has('seller_articles');
        } else {
            $query = $query->withCount('buyer_articles')
                ->with('buyer_articles')
                ->has('buyer_articles');
        }

        $categories = $query->get();

        return response()->json(['data' => CategoryResource::collection($categories)], 200);
    }

    public function getCategoryById(Request $request, $category_id) {
        $category = [];

        $query = Category::query();

        if ( $request->segment(4) == 'seller' ) {
            $query = $query->withCount('seller_articles')
                ->with('seller_articles')
                ->has('seller_articles');
        } else {
            $query = $query->withCount('buyer_articles')
                ->with('buyer_articles')
                ->has('buyer_articles');
        }

        $category = $query->where('id', $category_id)->first();

        return response()->json(['data' => new CategoryResource($category)], 200);
    }
}
