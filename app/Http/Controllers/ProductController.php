<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    
        public function index()
        {
            $products = Product::with('category')->get();
            return view('products.index', compact('products'));
        }
    
        public function create()
        {
            $categories = ProductCategory::all(); 
            return view('products.create', compact('categories'));
        }
    
        public function store(Request $request)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:product_categories,id',
            ]);
    
            Product::create($request->all());
    
            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        }
    
        public function edit(Product $product)
        {
            $categories = ProductCategory::all();
            return view('products.edit', compact('product', 'categories'));
        }
    
        public function update(Request $request, Product $product)
        {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:product_categories,id',
            ]);
    
            $product->update($request->all());
    
            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        }
    
        public function destroy(Product $product)
        {
            $product->delete();
            return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
        }
}
