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
    public function index(Request $request)
    {
        $colocationId = $request->get('colocation_id');

        $categories = Category::whereNull('colocation_id')
            ->when($colocationId, function ($query, $colocationId) {
                return $query->orWhere('colocation_id', $colocationId);
            })
            ->get();

        return response()->json($categories);
    }

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

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        if (is_null($category->colocation_id)) {
            return response()->json(['error' => 'Cannot edit global categories'], 403);
        }

        $category->update($request->only('name'));
        return response()->json($category);
    }

    public function destroy(Category $category)
    {
        if (is_null($category->colocation_id)) {
            return response()->json(['error' => 'Cannot delete global categories'], 403);
        }

        $category->delete();
        return response()->json(['message' => 'Category deleted']);
    }
}