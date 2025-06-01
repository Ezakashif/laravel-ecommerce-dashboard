@include('layouts.header')
@include('layouts.sidebar')
@include('layouts.navbar')

<div class="bg-light rounded h-100 p-4">
    <h6 class="mb-4">Products Table</h6>
    <div class="table-responsive">
        <a class="btn btn-primary btn-sm mb-2" href="{{ route('admin.products.create') }}">Add New Product</a>

        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Image</th>
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
                    <td style="width: 100px;">
                        @if ($product->images->count())
                            <img src="{{ asset('storage/' . $product->images->first()->path) }}" 
                                 alt="Product Image" 
                                 class="img-thumbnail" 
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <span class="text-muted">No Image</span>
                        @endif
                    </td>

                    <td>{{ $product->name }}</td>

                    <td>${{ number_format($product->base_price, 2) }}</td>

                    <td>
                        @forelse ($product->categories as $category)
                            @if ($category->parent)
                                {{ $category->parent->name }} →
                            @endif
                            {{ $category->name }}
                        @empty
                            <em>No active category</em>
                        @endforelse
                    </td>

                  <td>
    @if ($product->variants->count())
        <ul style="padding-left: 1rem; list-style: none;">
            @foreach($product->variants as $variant)
                <li class="mb-3">
                    <div><strong>SKU:</strong> {{ $variant->sku }}</div>
                    <div><strong>Size:</strong> {{ $variant->attributes['size'] }}</div>
                    <div>
                        <strong>Color:</strong>
                        <span style="display:inline-block;width:20px;height:20px;background-color:{{ $variant->attributes['color'] }};border:1px solid #ccc;"></span>
                        <small>{{ $variant->attributes['color'] }}</small>
                    </div>
                    @if($variant->price_override)
                        <div><strong>Override Price:</strong> ${{ $variant->price_override }}</div>
                    @endif

                    {{-- Variant images --}}
                    <div class="mt-2 d-flex gap-2 flex-wrap">
                        @if ($variant->images->count())
                            @foreach ($variant->images as $image)
                                <img src="{{ asset('storage/' . $image->path) }}" 
                                     alt="Variant Image" 
                                     style="width: 60px; height: 60px; object-fit: cover; border:1px solid #ccc; border-radius: 4px;">
                            @endforeach
                        @else
                            <div class="text-muted" style="font-size: 0.85rem;">No Image</div>
                        @endif
                    </div>

                    <hr>
                </li>
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
