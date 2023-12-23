@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Product List</h2>

    <div class="text-end mb-3">
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="fas fa-plus"></i> Add Product</button>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Description</th>
                <th></th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
            <tr>
                <td>{{ $product->id }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->description }}</td>
                <td></td>
                <td>
                    <div class="text-center">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#editProductModal{{ $product->id }}" title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>


                    <form method="post" action="{{ route('products.destroy', ['product' => $product->id]) }}" class="text-center">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn" title="Delete" onclick="return confirm('Are you sure you want to delete this product?')" style="color: red;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @include('modals.edit-product', ['product' => $product])
                    @include('modals.add-product')
                </td>
            </tr>

            @empty
            <tr>
                <td colspan="5">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{ $products->links('') }}

</div>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('success') }}',
    });
</script>
@endif
@endsection