<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Colocation;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Requests\CreateCategoryRequest;

class CategoryController extends Controller
{
    // get all categories : global and from the colocation
    public function index(Request $request)
    {
        $colocationId = $request->get('colocation_id');

        // find categories with no colocation or with the given colocation
        $categories = Category::whereNull('colocation_id')
            ->when($colocationId, function ($query, $colocationId) {
                return $query->orWhere('colocation_id', $colocationId);
            })
            ->get();

        return response()->json($categories);
    }

    // create a new category for a colocation
    public function store(CreateCategoryRequest $request)
    {
        $category = Category::create([
            'name' => $request->name,
            'colocation_id' => $request->colocation_id 
        ]);

        return response()->json([
            'message' => 'New personalized category added!',
            'category' => $category
        ], 201);
    }

    // update a category name but the global categories cannot be changed
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        // stop it if the category is global
        if (is_null($category->colocation_id)) {
            return response()->json(['error' => 'Cannot edit global categories'], 403);
        }

        $category->update($request->only('name'));
        return response()->json($category);
    }

    // delete a category but global categories cannot be deleted
    public function destroy(Category $category)
    {
        // stop if the category is global
        if (is_null($category->colocation_id)) {
            return response()->json(['error' => 'Cannot delete global categories'], 403);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted']);
    }
}