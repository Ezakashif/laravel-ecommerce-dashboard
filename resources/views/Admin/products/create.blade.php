@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

<div class="bg-light rounded h-100 p-4">
    <h6 class="mb-4">Add Product</h6>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Please correct the errors below.
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Product Info -->
        <div class="form-floating mb-3">
            <input type="text" name="name" class="form-control" placeholder="Product Name" required>
            <label>Product Name</label>
        </div>

        <div class="form-floating mb-3">
            <input type="number" name="base_price" class="form-control" placeholder="Base Price" required>
            <label>Base Price</label>
        </div>

        <div class="form-floating mb-3">
            <textarea name="description" class="form-control" placeholder="Description" style="height: 100px"></textarea>
            <label>Description</label>
        </div>

        <div class="form-floating mb-3">
            <select name="category_id" class="form-select" required>
                <option value="">-- Select Subcategory --</option>
                @foreach($subcategories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
            <label>Select Subcategory</label>
        </div>

        <!-- Product Images -->
        <div class="mb-3">
            <label class="form-label">Product Images (General)</label>
            <input type="file" name="product_images[]" class="form-control" multiple>
        </div>

        <hr>

        <!-- Variants -->
        <h5>Product Variants</h5>
        <div id="variant-wrapper">
            <div class="variant-row row g-2 mt-3" data-index="0">
                <div class="col-md-3">
                    <input type="text" name="variants[0][sku]" class="form-control" placeholder="SKU" required>
                </div>
                <div class="col-md-3">
                    <input type="text" name="variants[0][attributes][size]" class="form-control" placeholder="Size" required>
                </div>
                <div class="col-md-2 d-flex align-items-center gap-2">
                    <label class="form-label mb-0">Color:</label>
                    <input type="color" name="variants[0][attributes][color]" class="form-control form-control-color" value="#000000" required style="width: 60px;">
                </div>
                <div class="col-md-3">
                    <input type="number" step="0.01" name="variants[0][price_override]" class="form-control" placeholder="Price">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger" onclick="removeVariant(this)">X</button>
                </div>

                <div class="col-md-12 mt-2">
                    <label class="form-label">Variant Images</label>
                    <div class="variant-images" id="variant-images-0">
                        <div class="d-flex align-items-center mb-2">
                            <input type="file" name="variants[0][images][]" class="form-control">
                            <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removeImageInput(this)">X</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addVariantImage(this)">+ Add Image</button>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-secondary mt-3" id="add-variant">+ Add Variant</button>

        <hr>
        <button type="submit" class="btn btn-success w-100">Create Product</button>
    </form>
</div>

<script>
    let variantIndex = 1;

    document.getElementById('add-variant').addEventListener('click', function () {
        const wrapper = document.getElementById('variant-wrapper');

        const html = `
        <div class="variant-row row g-2 mt-3" data-index="${variantIndex}">
            <div class="col-md-3">
                <input type="text" name="variants[${variantIndex}][sku]" class="form-control" placeholder="SKU" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="variants[${variantIndex}][attributes][size]" class="form-control" placeholder="Size" required>
            </div>
            <div class="col-md-2 d-flex align-items-center gap-2">
                <label class="form-label mb-0">Color:</label>
                <input type="color" name="variants[${variantIndex}][attributes][color]" class="form-control form-control-color" value="#000000" required style="width: 60px;">
            </div>
            <div class="col-md-3">
                <input type="number" step="0.01" name="variants[${variantIndex}][price_override]" class="form-control" placeholder="Price">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger" onclick="removeVariant(this)">X</button>
            </div>

            <div class="col-md-12 mt-2">
                <label class="form-label">Variant Images</label>
                <div class="variant-images" id="variant-images-${variantIndex}">
                    <div class="d-flex align-items-center mb-2">
                        <input type="file" name="variants[${variantIndex}][images][]" class="form-control">
                        <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removeImageInput(this)">X</button>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addVariantImage(this)">+ Add Image</button>
            </div>
        </div>
        `;

        wrapper.insertAdjacentHTML('beforeend', html);
        variantIndex++;
    });

    function removeVariant(button) {
        button.closest('.variant-row').remove();
    }

    function addVariantImage(button) {
        const variantRow = button.closest('.variant-row');
        const index = variantRow.getAttribute('data-index');
        const container = document.getElementById(`variant-images-${index}`);

        const div = document.createElement('div');
        div.classList.add('d-flex', 'align-items-center', 'mb-2');

        div.innerHTML = `
            <input type="file" name="variants[${index}][images][]" class="form-control">
            <button type="button" class="btn btn-sm btn-danger ms-2" onclick="removeImageInput(this)">X</button>
        `;

        container.appendChild(div);
    }

    function removeImageInput(button) {
        button.parentElement.remove();
    }
</script>

@include('layouts.footer')
