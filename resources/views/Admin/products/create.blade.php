@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

                        <div class="bg-light rounded h-100 p-4">
                            <h6 class="mb-4">Add Product</h6>
                            <div class="form-floating mb-3">
                              <form action="{{ route('admin.products.store') }}" method="POST">
    @csrf

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

    <button type="submit" class="btn btn-success w-100">Create Product</button>
</form>
              

@include('layouts.footer')