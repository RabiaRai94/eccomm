<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::all();
        return view('admin.productcategories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.productcategories.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:product_categories,name']);
        ProductCategory::create($request->only('name'));
        return response()->json(['message' => 'Category created successfully']);
    }

    public function edit($id)
    {
        $category = ProductCategory::findOrFail($id);
        return view('admin.productcategories.edit', compact('category'));
    }

   public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required|string|unique:product_categories,name,' . $id]);
        $category = ProductCategory::findOrFail($id);
        $category->update($request->only('name'));
        return response()->json(['message' => 'Category updated successfully']);
    }

    public function destroy($id)
    {
        $category = ProductCategory::findOrFail($id);
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
}
