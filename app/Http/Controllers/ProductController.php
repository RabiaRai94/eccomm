<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function getCategories()
    {
        $categories = ProductCategory::select('id', 'name')->get();
        return response()->json($categories);
    }

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
    public function getLandingProducts(Request $request)
    {
        $query = Product::with(['category', 'variants.attachments']);
        if ($request->category && $request->category !== 'all') {
            $query->where('category_id', $request->category);
        }

        $products = $query->get();

        $productsData = $products->map(function ($product) {
            $cardHtml = "
            <div class='card mb-3' style='width: 18rem; margin: 10px;'>
                <div class='card-body text-center'>
                    <h5 class='card-title'>{$product->name}</h5>
                    <p class='card-text'>Category: {$product->category->name}</p>
                    <div class='d-flex flex-wrap justify-content-center'>";

            foreach ($product->variants as $variant) {
                $imagePath = $variant->attachments->first()->file_path ?? 'default-image.jpg';
                $cardHtml .= "
                <div class='card m-2' style='width: 12rem;'>
                
                    <img src='" . asset("storage/{$imagePath}") . "' class='card-img-top' alt='{$variant->size}' style='height: 250px; object-fit: cover;'>
                    <div class='card-body'>
                        <h6 class='card-subtitle mb-2 text-muted'>Size: {$variant->size}</h6>
                        <p class='card-text'>Price: {$variant->price}</p>
                        <p class='card-text'>Stock: {$variant->stock}</p>
                        <button 
                            class='btn btn-primary btn-sm add-to-cart' 
                            data-product-id='{$product->id}' 
                            data-variant-id='{$variant->id}' 
                            data-variant-size='{$variant->size}' 
                            data-variant-price='{$variant->price}'>
                            Add to Cart
                        </button>
                        
                    </div>
                </div>";
            }

            $cardHtml .= "</div>
                </div>
            </div>";
            return ['card' => $cardHtml];
        });

        return response()->json($productsData);
    }

    public function getProducts()
    {
        $products = Product::with('variants')->get();

        return DataTables::of($products)
            ->addColumn('variants', function ($product) {
                $variantHtml = '';
                foreach ($product->variants as $variant) {
                    $variantHtml .= "<div class='card'>
                                        <h5>{$variant->name}</h5>
                                        <p>Price: {$variant->price}</p>
                                     </div>";
                }
                return $variantHtml;
            })
            ->rawColumns(['variants'])
            ->make(true);
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
