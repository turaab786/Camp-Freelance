<?php

namespace App\Http\Controllers\Api\Gig;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Model\Gig\GigCategory;
use App\Model\Gig\GigServiceType;

use App\Http\Resources\Gig\GigCategoryResource;
use App\Http\Resources\Gig\GigParentCategoryResource;
use App\Http\Resources\Gig\GigCategoryServiceTypeResource;

class ApiGigCategoryController extends Controller
{
    public function index()
    {
        $items = GigCategory::with('subcategories')->get();
        return response()->json(['data' => GigCategoryResource::collection($items)], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'is_parent' => 'required|boolean',
            'sort_order' => 'numeric',
            'parent_id' => 'numeric'
        ]);
        
        $result = GigCategory::create([
            'title' => $request->title,
            'description' => $request->description,
            'is_parent' => $request->is_parent,
            'sort_order' => $request->sort_order ? $request->sort_order : 0,
            'parent_id' => $request->parent_id ? $request->parent_id : null
        ]);

        if ($result) {
            return response()->json(['data' => 'Created'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    public function show($id)
    {
        $item = GigCategory::where('id', $id)->with('subcategories')->first();
        if ($item) {
            return response()->json(['data' => new GigCategoryResource($item)], 200);
        } else {
            return response()->json(['message' => 'No Category Found!'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $item = GigCategory::where('id', $id)->first();
        
        $result = GigCategory::where('id', $id)->update([
            'title' => $request->title ? $request->title : $item->title,
            'description' => $request->description ? $request->description : $item->description,
            'is_parent' => $request->is_parent,
            'sort_order' => $request->sort_order ? $request->sort_order : $item->sort_order,
            'parent_id' => $request->parent_id ? $request->parent_id : $item->parent_id
        ]);

        if ($result) {
            return response()->json(['data' => 'Updated!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    public function destroy($id)
    {
        $result = GigCategory::where('id', $id)->delete();
        if ($result) {
            return response()->json(['data' => 'Deleted!'], 200);
        } else {
            return response()->json(['message' => 'Error Occured!'], 500);
        }
    }

    public function getParentCategories(Request $request)
    {
        $categories = GigCategory::where('is_parent', true)->get();
        return response()->json(['data' => GigParentCategoryResource::collection($categories)], 200);
    }

    public function getChildCategories(Request $request, $parentId)
    {
        $categories = GigCategory::where('parent_id', $parentId)->get();
        return response()->json(['data' => GigParentCategoryResource::collection($categories)], 200);
    }

    public function getCategoryServiceTypes(Request $request, $childID)
    {
        $servicetypes = GigServiceType::where('category_id', $childID)->get();
        return response()->json(['data' => GigCategoryServiceTypeResource::collection($servicetypes)], 200);
    }
}
