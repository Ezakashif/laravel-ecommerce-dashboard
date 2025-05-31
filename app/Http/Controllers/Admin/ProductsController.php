<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;


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

                'variants' => 'required|array',
                'variants.*.sku' => 'required|string|distinct|unique:product_variants,sku',
                'variants.*.attributes.size' => 'required|string',
                'variants.*.attributes.color' => 'required|string',
                'variants.*.price_override' => 'nullable|numeric',
            ]);

            // Create the product
            $product = Product::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'base_price' => $validated['base_price'],
            ]);

            // Attach category
            $product->categories()->attach($validated['category_id']);

            // Create each variant
            foreach ($validated['variants'] as $variant) {
                $product->variants()->create([
                    'sku' => $variant['sku'],
                    'attributes' => $variant['attributes'],
                    'price_override' => $variant['price_override'] ?? null,
                ]);
            }

            return redirect()->route('admin.products.index')->with('success', 'Product and variants created.');
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

        // Only validate variants if present
        'variants' => 'nullable|array',
        'variants.*.sku' => 'required|string|distinct',
        'variants.*.attributes.size' => 'nullable|string',
        'variants.*.attributes.color' => 'required|string',
        'variants.*.price_override' => 'nullable|numeric',
    ]);

    // Update the product itself
    $product->update([
        'name' => $validated['name'],
        'base_price' => $validated['base_price'],
        'description' => $validated['description'] ?? null,
    ]);

    // Sync category
    $product->categories()->sync([$validated['category_id']]);

    // Create new variants (skip duplicate SKUs manually if needed)
    if (!empty($validated['variants'])) {
        foreach ($validated['variants'] as $variant) {
            // Prevent duplicate SKUs from being inserted
            if (!\App\Models\ProductVariant::where('sku', $variant['sku'])->exists()) {
                $product->variants()->create([
                    'sku' => $variant['sku'],
                    'attributes' => $variant['attributes'],
                    'price_override' => $variant['price_override'] ?? null,
                ]);
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
