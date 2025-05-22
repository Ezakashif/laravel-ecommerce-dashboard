@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

<div class="bg-light rounded h-100 p-4">
    <h6 class="mb-4">Products Table</h6>
    <div class="table-responsive">
         <a class="btn btn-success btn-sm mb-2" href="{{ route('admin.products.create') }}">Add New Product</a>
      <table class="table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Base Price</th>
            <th>Category</th>
            <th>Variants</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
    @foreach ($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>${{ number_format($product->base_price, 2) }}</td>

            <td>
                @foreach ($product->categories as $category)
                    @if ($category->parent)
                        {{ $category->parent->name }} → 
                    @endif
                    {{ $category->name }}
                @endforeach
            </td>

            <td>
                @if ($product->variants->count())
                    <ul style="padding-left: 1rem;">
                       @foreach($product->variants as $variant)
                    <div class="mb-1">
                        <strong>SKU:</strong> {{ $variant->sku }} <br>
                        <strong>Size:</strong> {{ $variant->attributes['size'] }} <br>
                        <strong>Color:</strong>
                        <span style="display:inline-block;width:20px;height:20px;background-color:{{ $variant->attributes['color'] }};border:1px solid #ccc;"></span>
                        <small>{{ $variant->attributes['color'] }}</small>
                        <br>
                        @if($variant->price_override)
                            <strong>Override Price:</strong> ${{ $variant->price_override }}
                        @endif
                    </div>
                @endforeach

                    </ul>
                @else
                    <em>No variants</em>
                @endif
            </td>

            <td>
                <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">Edit</a>
                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button onclick="return confirm('Delete this product?')" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>

    </div>
</div>

@include('layouts.footer')
