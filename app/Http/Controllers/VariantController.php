<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\Request;
use App\Models\ProductVariant;



class VariantController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|integer',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'attachments' => 'nullable|array',
            'attachments.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $variant = ProductVariant::create([
            'product_id' => $request->product_id,
            'size' => $request->size,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('attachments', 'public');

                Attachment::create([
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'attachable_type' => ProductVariant::class,
                    'attachable_id' => $variant->id,
                    'file_path' => $path,
                ]);
            }
        }

        return response()->json(['message' => 'Product variant created successfully']);
    }
    public function fetchVariants($productId)
    {
        $variants = ProductVariant::where('product_id', $productId)
            ->with('attachments')
            ->get();
    
        return datatables()->of($variants)
            ->addColumn('card', function ($variant) {
                return view('admin.products.partial.varient_card', compact('variant'))->render();
            })
            ->rawColumns(['card']) 
            ->make(true);
    }
    
    

    public function destroy($id)
    {
        $variant = ProductVariant::findOrFail($id);
        $variant->delete();

        return response()->json([
            'success' => true,
            'message' => 'Variant deleted successfully!',
        ]);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'size' => 'required|numeric',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);

        $variant = ProductVariant::findOrFail($id);
        $variant->size = $request->size;
        $variant->price = $request->price;
        $variant->stock = $request->stock;

        if ($request->hasFile('attachments')) {
        }

        $variant->save();

        return response()->json([
            'success' => true,
            'message' => 'Variant updated successfully!',
        ]);
    }
}
