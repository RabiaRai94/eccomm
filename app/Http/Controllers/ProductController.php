<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $products = Product::with('category')->select('id', 'name', 'description', 'category_id');
            
            return datatables()->of($products)
                ->addColumn('actions', function ($product) {
                    return view('admin.products.partial.productactions', compact('product'))->render();
                })
                ->rawColumns(['actions']) 
            ->make(true);
        }
        
        return view('admin.products.index');
    }

    public function create()
    {
        $categories = ProductCategory::all();
        return view('admin.products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:products,name',
            'category_id' => 'required|exists:product_categories,id'
        ]);

        $product = Product::create($request->only('name', 'description', 'category_id'));

        return response()->json(['message' => 'Product created successfully']);
    }
    

    public function show(Product $product)
    {
        $product->load('category');

        return view('admin.products.show', compact('product'));
    }
    public function edit($id)
    {
        $categories = ProductCategory::all();
        $product = Product::findOrFail($id);
        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|unique:products,name,' . $id,
            'category_id' => 'required|exists:product_categories,id'
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->only('name', 'description', 'category_id'));

        return response()->json(['message' => 'Product updated successfully']);
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}
