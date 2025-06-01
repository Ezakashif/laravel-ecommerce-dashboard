@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

<div class="bg-light rounded h-100 p-4">
    <h4 class="mb-4">Edit Product</h4>

    <!-- Main Product Update Form -->
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Product Fields -->
        <div class="form-floating mb-3">
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
            <label>Product Name</label>
        </div>

        <div class="form-floating mb-3">
            <input type="number" name="base_price" class="form-control" value="{{ old('base_price', $product->base_price) }}" required>
            <label>Base Price</label>
        </div>

        <div class="form-floating mb-3">
            <textarea name="description" class="form-control" style="height: 100px">{{ old('description', $product->description) }}</textarea>
            <label>Description</label>
        </div>

        <div class="form-floating mb-3">
            <select name="category_id" class="form-select" required>
                @foreach($subcategories as $cat)
                    <option value="{{ $cat->id }}" {{ $product->categories->first()->id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            <label>Select Subcategory</label>
        </div>

        <div class="mb-3">
            <label>Update Product Images (General)</label>
            <input type="file" name="product_images[]" class="form-control" multiple>
        </div>

        <hr>

        <!-- Add New Variants -->
        <h5>Add New Variants</h5>
        <div id="new-variants"></div>
        <button type="button" class="btn btn-secondary mb-3" id="add-variant">+ Add Variant</button>

        <!-- Submit -->
        <button type="submit" class="btn btn-success w-100 mt-2">Update Product & Add Variants</button>
    </form>

    <hr>

    <!-- Existing Variants -->
    <h5>Existing Variants</h5>
    @foreach($product->variants as $variant)
        <div class="row g-2 mb-4 p-3 bg-white rounded border">

            <!-- Variant Update Form -->
            <form action="{{ route('admin.productVariants.update', $variant->id) }}" method="POST" enctype="multipart/form-data" class="col-md-11 row g-2">
                @csrf
                @method('PUT')

                <div class="col-md-3">
                    <label>SKU</label>
                    <input type="text" name="sku" class="form-control" value="{{ $variant->sku }}" required>
                </div>

                <div class="col-md-2">
                    <label>Size</label>
                    <input type="text" name="attributes[size]" class="form-control" value="{{ $variant->attributes['size'] ?? '' }}">
                </div>

                <div class="col-md-2">
                    <label>Color</label>
                    <input type="color" name="attributes[color]" class="form-control form-control-color" value="{{ $variant->attributes['color'] ?? '#000000' }}">
                </div>

                <div class="col-md-2">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price_override" class="form-control" value="{{ $variant->price_override }}">
                </div>

                <div class="col-md-3">
                    <label>Add More Images</label>
                    <div class="variant-images-wrapper">
                        <div class="input-group mb-2">
                            <input type="file" name="variant_images[]" class="form-control">
                            <button type="button" class="btn btn-danger remove-image">&times;</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary add-variant-image">+ Add Image</button>
                </div>

                <!-- Submit -->
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary mt-2">Update Variant</button>
                </div>
            </form>

            <!-- Delete Variant -->
            <div class="col-md-1 d-flex align-items-start justify-content-end">
                <form action="{{ route('admin.productVariants.destroy', $variant->id) }}" method="POST" onsubmit="return confirm('Delete this variant?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm mt-2">X</button>
                </form>
            </div>

            <!-- Show Existing Images -->
            <div class="col-md-12 mt-2">
                <strong>Images:</strong>
                @if ($variant->images->count())
                    @foreach ($variant->images as $img)
                        <img src="{{ asset('storage/' . $img->path) }}" alt="Variant Image" style="height: 60px; width: auto; margin-right: 5px;">
                    @endforeach
                @else
                    <em>No images</em>
                @endif
            </div>
        </div>
    @endforeach
</div>

<!-- Scripts -->
<script>
    let variantIndex = 0;

    document.getElementById('add-variant').addEventListener('click', function () {
        const wrapper = document.getElementById('new-variants');
        const html = `
            <div class="row g-2 align-items-end mb-3 variant-block">
                <div class="col-md-3"><input type="text" name="variants[${variantIndex}][sku]" class="form-control" placeholder="SKU" required></div>
                <div class="col-md-2"><input type="text" name="variants[${variantIndex}][attributes][size]" class="form-control" placeholder="Size"></div>
                <div class="col-md-2"><input type="color" name="variants[${variantIndex}][attributes][color]" class="form-control" value="#000000"></div>
                <div class="col-md-2"><input type="number" name="variants[${variantIndex}][price_override]" class="form-control" placeholder="Price"></div>
                <div class="col-md-3">
                    <div class="variant-images-wrapper">
                        <div class="input-group mb-2">
                            <input type="file" name="variants[${variantIndex}][images][]" class="form-control">
                            <button type="button" class="btn btn-danger remove-image">&times;</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary add-variant-image" data-index="${variantIndex}">+ Add Image</button>
                </div>
                <div class="col-12"><hr></div>
            </div>
        `;
        wrapper.insertAdjacentHTML('beforeend', html);
        variantIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('add-variant-image')) {
            const index = e.target.getAttribute('data-index');
            const wrapper = e.target.previousElementSibling;
            const newInput = document.createElement('div');
            newInput.classList.add('input-group', 'mb-2');
            newInput.innerHTML = `
                <input type="file" name="variants[${index}][images][]" class="form-control">
                <button type="button" class="btn btn-danger remove-image">&times;</button>
            `;
            wrapper.appendChild(newInput);
        }

        if (e.target.classList.contains('remove-image')) {
            e.target.closest('.input-group').remove();
        }
    });
</script>

@include('layouts.footer')
