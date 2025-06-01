<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductVariant;

class ProductVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
  
       public function update(Request $request, ProductVariant $productVariant)
{
    $validated = $request->validate([
        'sku' => 'required|string|unique:product_variants,sku,' . $productVariant->id,
        'attributes.size' => 'nullable|string',
        'attributes.color' => 'required|string',
        'price_override' => 'nullable|numeric',
        'images.*' => 'nullable|image|max:2048',  // Validate images if any
        'delete_images' => 'nullable|array',
        'delete_images.*' => 'integer|exists:variant_images,id',
    ]);

    $productVariant->update([
        'sku' => $validated['sku'],
        'attributes' => $validated['attributes'],
        'price_override' => $validated['price_override'],
    ]);

    // Delete selected images
    if (!empty($validated['delete_images'])) {
        foreach ($validated['delete_images'] as $imgId) {
            $image = $productVariant->images()->find($imgId);
            if ($image) {
                \Storage::delete($image->path);
                $image->delete();
            }
        }
    }

    // Save new uploaded images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $imageFile) {
            $path = $imageFile->store('variant_images', 'public');
            $productVariant->images()->create(['path' => $path]);
        }
    }

    return back()->with('success', 'Variant updated successfully.');
}

    

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
 
         public function destroy(ProductVariant $productVariant)
    {
        $productVariant->delete();
        return back()->with('success', 'Variant deleted successfully.');
    }
    
}
