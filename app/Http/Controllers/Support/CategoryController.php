<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use App\Http\Requests\Support\CategoryRequest;
use App\Support\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumbs = [
            ['link'=>"dashboard",'name'=>"Home"], ['name'=>"Category"]
        ];
        return view('/support/category/list ', [
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {

        $validated = $request->validated();
        $category = new Category();

        $slug = strtolower(str_replace(' ', '_', $request->name));

        $category->name = $request->name;
        $category->parent_id = $request->parent;
        $category->icon = $request->icon;
        $category->slug = $slug;
        $category->created_by_id = auth()->user()->id;
        $saved = $category->save();

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
    public function getCategory($id)
    {
        $category = Category::findOrFail($id);
        return response()->json(['category' => $category], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $validated = $request->validated();
        $category = Category::find($id);

        $category->name = $request->name;
        $category->parent_id = $request->parent;
        $category->icon = $request->icon;
        $category->slug = $request->slug;
        $category->updated_by_id = auth()->user()->id;
        $saved = $category->save();

        if ( !$saved ) {
            return response()->json(['message' => 'Error Occured!'], 500);
        }

        return response()->json(['message' => 'Updated Successfully!'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();

        if (!$category->trashed()) {
            return response()->json(['message' => 'Error Occured!'], 500);
        }

        return response()->json(['message' => 'Deleted Successfully!'], 200);
    }

    public function getCategories(Request $request, $for = 'dropdown') {
        if ( $for == 'dropdown' ) {
            $categories = Category::all();
            return response()->json(['categories' => $categories], 200);
        } else if ( $for == 'list' ) {

            $query = Category::with('parent');;

            if ( $request->has('name') && !empty($request->input('name')) ) {
                $query = $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ( $request->has('parent') && !empty($request->input('parent')) ) {
                $query = $query->where('parent_id', $request->parent);
            }

            $categories = $query->get();


            return response()->json(['categories' => $categories], 200);
        }
    }

    public function getCategoriesWithChildren() {
        $categories = Category::with('children')->get();
        return response()->json(['categories' => $categories], 200);
    }
}
