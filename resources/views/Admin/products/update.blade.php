@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

<div class="bg-light rounded h-100 p-4">
    <h6 class="mb-4">Edit Product</h6>

    <!-- Main Product Update Form -->
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
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
                    <option value="{{ $cat->id }}" {{ $product->categories->first()->id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            <label>Select Subcategory</label>
        </div>

        <hr>

        <!-- New Variants -->
        <h5>Add New Variants</h5>
        <div id="new-variants"></div>
        <button type="button" class="btn btn-secondary my-2" id="add-variant">Add New Variant</button>

        <!-- Final Submit Button -->
        <button type="submit" class="btn btn-success w-100 mt-3">Update Product & Add Variants</button>
    </form>

    <hr>

    <!-- Existing Variants -->
    <h5>Existing Variants</h5>
    @foreach($product->variants as $variant)
        <div class="row g-2 align-items-end mb-3 border p-3 bg-white rounded">
            <form action="{{ route('admin.productVariants.update', $variant->id) }}" method="POST" class="col-md-11 row g-2">
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

                <div class="col-md-3 d-flex align-items-center">
                    <label class="me-2">Color</label>
                    <input type="color" name="attributes[color]" class="form-control form-control-color"
                        value="{{ $variant->attributes['color'] ?? '#000000' }}">
                </div>

                <div class="col-md-2">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price_override" class="form-control"
                        value="{{ $variant->price_override }}">
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100 mt-4">Update</button>
                </div>
            </form>

            <!-- Delete Button -->
            <div class="col-md-1 mt-4">
                <form action="{{ route('admin.productVariants.destroy', $variant->id) }}" method="POST" onsubmit="return confirm('Delete this variant?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">X</button>
                </form>
            </div>
        </div>
    @endforeach
</div>

<!-- Variant JS -->
<script>
    let variantIndex = 0;

    document.getElementById('add-variant').addEventListener('click', function () {
        const wrapper = document.getElementById('new-variants');

        const html = `
        <div class="row g-2 align-items-end mb-2 variant-block">
            <div class="col-md-3">
                <input type="text" name="variants[${variantIndex}][sku]" class="form-control" placeholder="SKU" required>
            </div>
            <div class="col-md-2">
                <input type="text" name="variants[${variantIndex}][attributes][size]" class="form-control" placeholder="Size">
            </div>
            <div class="col-md-3 d-flex align-items-center">
                <label class="me-2">Color</label>
                <input type="color" name="variants[${variantIndex}][attributes][color]" class="form-control form-control-color" value="#000000">
            </div>
            <div class="col-md-2">
                <input type="number" step="0.01" name="variants[${variantIndex}][price_override]" class="form-control" placeholder="Price">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-variant">X</button>
            </div>
        </div>
        `;

        wrapper.insertAdjacentHTML('beforeend', html);
        variantIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-variant')) {
            e.target.closest('.variant-block').remove();
        }
    });
</script>

@include('layouts.footer')
