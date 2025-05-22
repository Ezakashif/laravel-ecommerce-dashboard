@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">Add Product</h6>
                            <div class="form-floating mb-3">
                           <form action="{{ route('admin.products.store') }}" method="POST">
    @csrf

    <!-- Product Fields -->
    <div class="form-floating mb-3">
        <input type="text" name="name" class="form-control" placeholder="Product Name" required>
        <label>Product Name</label>
    </div>

    <div class="form-floating mb-3">
        <input type="text" name="base_price" class="form-control" placeholder="Base Price" required>
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

    <hr>

    <!-- Variant Section -->
    <div id="variant-wrapper">
        <h5>Product Variants</h5>
        <div class="variant-row row g-2">
            <div class="col-md-3">
                <input type="text" name="variants[0][sku]" class="form-control" placeholder="SKU" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="variants[0][attributes][size]" class="form-control" placeholder="Size" required>
            </div>
            <div class="col-md-2">
                <div class="d-flex align-items-center gap-2">
                <label for="variant-color-0" class="form-label mb-0">Color:</label>
                <input type="color" id="variant-color-0" name="variants[0][attributes][color]" class="form-control form-control-color" value="#000000" required style="width: 50px; height: 38px;">
            </div>
            </div>
            <div class="col-md-3">
                <input type="number" step="1.00" name="variants[0][price_override]" class="form-control" placeholder="Price">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger" onclick="removeVariant(this)">X</button>
            </div>
        </div>
    </div>

    <button type="button" class="btn btn-secondary mt-2" id="add-variant">Add Variant</button>

    <hr>

    <button type="submit" class="btn btn-success w-100">Create Product</button>
</form>

              
<script>
    let variantIndex = 1;

    document.getElementById('add-variant').addEventListener('click', function () {
        const wrapper = document.getElementById('variant-wrapper');

        const html = `
        <div class="variant-row row g-2 mt-2">
            <div class="col-md-3">
                <input type="text" name="variants[${variantIndex}][sku]" class="form-control" placeholder="SKU" required>
            </div>
            <div class="col-md-3">
                <input type="text" name="variants[${variantIndex}][attributes][size]" class="form-control" placeholder="Size" required>
            </div>
            <div class="col-md-2 d-flex align-items-center gap-2">
                <label for="variant-color-${variantIndex}" class="form-label mb-0">Color:</label>
                <input type="color" id="variant-color-${variantIndex}" name="variants[${variantIndex}][attributes][color]" class="form-control form-control-color" value="#000000" required style="width: 60px; height: 38px;">
            </div>
            <div class="col-md-3">
                <input type="number" step="0.01" name="variants[${variantIndex}][price_override]" class="form-control" placeholder="Price">
            </div>
            <div class="col-md-1">
                <button type="button" class="btn btn-danger" onclick="removeVariant(this)">X</button>
            </div>
        </div>
        `;


        wrapper.insertAdjacentHTML('beforeend', html);
        variantIndex++;
    });

    function removeVariant(button) {
        button.closest('.variant-row').remove();
    }
</script>

@include('layouts.footer')