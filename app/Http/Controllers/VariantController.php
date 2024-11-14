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
    
}
