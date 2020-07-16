<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Http\Requests\Support\ArticleRequest;
use App\Support\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $breadcrumbs = [
            ['link'=>"dashboard",'name'=>"Home"], ['name'=>"Article"]
        ];
        return view('/support/article/list', [
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $breadcrumbs = [
            ['link'=>"dashboard",'name'=>"Home"],
            ['link' => url('/support-article'), 'name'=>"Articles"],
            ['name'=>"Create"],
        ];
        return view('/support/article/create', [
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArticleRequest $request)
    {
        $validated = $request->validated();
        $article = new Article();

        $slug = strtolower(str_replace(' ', '_', $request->title));

        $article->title = $request->title;
        $article->content = $request->description;
        $article->belongs_to = $request->belongsToType;
        $article->slug = $slug;
        $article->category_id = Arr::first($request->categories);
        $article->created_by_id = auth()->user()->id;
        $saved = $article->save();

        if ( !$saved ) {
            return response()->json(['message' => 'Error Occured!'], 500);
        }

        return response()->json(['message' => 'Added Successfully!'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $breadcrumbs = [
            ['link'=> url('/dashboard'),'name'=>"Home"],
            ['link' => url('/support-article'), 'name'=>"Articles"],
            ['name' => "Edit"]
        ];
        return view('/support/article/edit', [
            'breadcrumbs' => $breadcrumbs
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArticleRequest $request, $id)
    {
        $validated = $request->validated();
        $article = Article::find($id);

        $article->title = $request->title;
        $article->content = $request->description;
        $article->belongs_to = $request->belongsToType;
        $article->slug = $request->slug;
        $article->category_id = Arr::first($request->categories);
        $article->updated_by_id = auth()->user()->id;
        $saved = $article->save();

        if ( !$saved ) {
            return response()->json(['message' => 'Error Occured!'], 500);
        }

        return response()->json(['message' => 'Added Successfully!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::find($id);
        $article->delete();

        if (!$article->trashed()) {
            return response()->json(['message' => 'Error Occured!'], 500);
        }

        return response()->json(['message' => 'Deleted Successfully!'], 200);
    }

    public function getArticles(Request $request) {
        $query = Article::with('category');;

        if ( $request->has('title') && !empty($request->input('title')) ) {
            $query = $query->where('title', 'like', '%' . $request->title . '%');
        }

        if ( $request->has('category') && !empty($request->input('category')) ) {
            $query = $query->where('category_id', $request->category);
        }

        if ( $request->has('belongsTo') && !empty($request->input('belongsTo')) ) {
            $query = $query->where('belongs_to', $request->belongsTo);
        }

        $articles = $query->get();
        return response()->json(['articles' => $articles], 200);
    }

    public function getArticle($id) {
        $article = Article::find($id);
        return response()->json(['article' => $article], 200);
    }
 }
