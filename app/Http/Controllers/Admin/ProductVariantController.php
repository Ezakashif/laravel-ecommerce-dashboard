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
        ]);

        $productVariant->update([
            'sku' => $validated['sku'],
            'attributes' => $validated['attributes'],
            'price_override' => $validated['price_override'],
        ]);

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
