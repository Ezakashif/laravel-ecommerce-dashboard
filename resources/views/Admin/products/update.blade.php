@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">Add Product</h6>
                            <div class="form-floating mb-3">
   <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="form-floating mb-3">
        <input type="text" name="name" class="form-control" value="{{ old('name', $product->name) }}" required>
        <label>Product Name</label>
    </div>

    <div class="form-floating mb-3">
        <input type="text" name="base_price" class="form-control" value="{{ old('base_price', $product->base_price) }}" required>
        <label>Base Price</label>
    </div>

    <div class="form-floating mb-3">
        <textarea name="description" class="form-control" style="height: 100px">{{ old('description', $product->description) }}</textarea>
        <label>Description</label>
    </div>

    <div class="form-floating mb-3">
        <select name="category_id" class="form-select" required>
            <option value="">-- Select Subcategory --</option>
            @foreach($subcategories as $cat)
                <option value="{{ $cat->id }}"
                    {{ (old('category_id', $product->categories->first()->id ?? '') == $cat->id) ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>
        <label>Select Subcategory</label>
    </div>

    <button type="submit" class="btn btn-primary w-100">Update Product</button>
</form>

              

@include('layouts.footer')