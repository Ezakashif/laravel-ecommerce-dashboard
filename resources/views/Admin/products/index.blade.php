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
                    <th>#</th>
                    <th>Product Id</th>
                    <th>Name</th>
                    <th>Main Category</th>
                    <th>Sub Category</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    @php
                        // Assuming the product is assigned to only one subcategory
                        $subcategory = $product->categories->first();
                        $mainCategory = $subcategory?->parent;
                    @endphp
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $mainCategory->name ?? '—' }}</td>
                        <td>{{ $subcategory->name ?? '—' }}</td>
                        <td>{{ $product->created_at->format('Y-m-d') }}</td>
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
