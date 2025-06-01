<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Storage;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    $products = Product::whereHas('categories', function ($q) {
        $q->whereNull('deleted_at');
    })
    ->with([
        'categories' => function ($q) {
            $q->whereNull('deleted_at');
        },
        'categories.parent',
        'variants'
    ])
    ->paginate(10);

    return view('admin.products.index', compact('products'));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $subcategories = Category::whereNotNull('parent_id')->get();
        return view('admin.products.create', compact('subcategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
     

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string',
        'base_price' => 'required|numeric',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'product_images.*' => 'nullable|image',

        'variants' => 'required|array',
        'variants.*.sku' => 'required|string|distinct|unique:product_variants,sku',
        'variants.*.attributes.size' => 'required|string',
        'variants.*.attributes.color' => 'required|string',
        'variants.*.price_override' => 'nullable|numeric',
        'variants.*.images.*' => 'nullable|image',
    ]);

    $product = Product::create([
        'name' => $validated['name'],
        'description' => $validated['description'] ?? null,
        'base_price' => $validated['base_price'],
    ]);

    $product->categories()->attach($validated['category_id']);

    if ($request->hasFile('product_images')) {
        foreach ($request->file('product_images') as $index => $image) {
            $path = $image->store("products/{$product->id}", 'public');

            $product->images()->create([
                'path' => $path,
                'is_primary' => $index === 0,
                'sort_order' => $index,
            ]);
        }
    }

    foreach ($validated['variants'] as $index => $variant) {
        $variantModel = $product->variants()->create([
            'sku' => $variant['sku'],
            'attributes' => $variant['attributes'],
            'price_override' => $variant['price_override'] ?? null,
        ]);

        $variantImages = data_get($request->variants, "{$index}.images");

        if ($variantImages && is_array($variantImages)) {
            foreach ($variantImages as $imgIndex => $imgFile) {
                $path = $imgFile->store("products/variants/{$variant['sku']}", 'public');

                $variantModel->images()->create([
                    'path' => $path,
                    'is_primary' => $imgIndex === 0,
                    'sort_order' => $imgIndex,
                ]);
            }
        }
    }

    return redirect()->route('admin.products.index')->with('success', 'Product and variants created with images.');
}



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $product = Products::find($id);
        // return $product;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::with('categories')->findOrFail($id);
        $subcategories = Category::whereNotNull('parent_id')->get();
        return view('admin.products.update', compact('product','subcategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
 public function update(Request $request, Product $product)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'base_price' => 'required|numeric',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'variants' => 'nullable|array',
        'variants.*.sku' => 'required|string|distinct',
        'variants.*.attributes.size' => 'nullable|string',
        'variants.*.attributes.color' => 'required|string',
        'variants.*.price_override' => 'nullable|numeric',
        'variants.*.images.*' => 'nullable|image|max:2048',
    ]);

    // Update product
    $product->update([
        'name' => $validated['name'],
        'base_price' => $validated['base_price'],
        'description' => $validated['description'] ?? null,
    ]);

    $product->categories()->sync([$validated['category_id']]);

    // Add new variants
    if (!empty($validated['variants'])) {
        foreach ($validated['variants'] as $index => $variant) {
            if (!\App\Models\ProductVariant::where('sku', $variant['sku'])->exists()) {
                $newVariant = $product->variants()->create([
                    'sku' => $variant['sku'],
                    'attributes' => $variant['attributes'],
                    'price_override' => $variant['price_override'] ?? null,
                ]);

                // Save uploaded images for this variant
                if (isset($request->variants[$index]['images'])) {
                    foreach ($request->variants[$index]['images'] as $imageFile) {
                        $path = $imageFile->store('variant_images', 'public');
                        $newVariant->images()->create(['path' => $path]);
                    }
                }
            }
        }
    }

    return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
}



    public function destroyVariant($variantId)
{
    $variant = ProductVariant::findOrFail($variantId);
    $variant->delete();

    return redirect()->back()->with('success', 'Product variant deleted successfully.');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
{
    $product->delete();

    return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
}
}
