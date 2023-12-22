@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Product List</h2>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Description</th>
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
                <td>
                <a href="#" class="btn addToCartBtn" title="Add to Cart" data-product-id="{{ $product->id }}">
                    <i class="fas fa-shopping-cart"></i>
                </a>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script>
    $(document).ready(function() {
        $('.addToCartBtn').on('click', function(e) {
            e.preventDefault();

            var productId = $(this).data('product-id');

            // Add CSRF token to the data
            var data = {
                "_token": "{{ csrf_token() }}",
                "productId": productId
            };

            // Make AJAX request to addToCart endpoint
            $.ajax({
                type: 'POST',
                url: '/cart/add/' + productId,
                data: data, // Include CSRF token in the request
                dataType: 'json',
                success: function(response) {
                    // Update the total quantity in the navbar
                    $('#totalQuantity').text(response.totalQuantity);

                    // Display a SweetAlert success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Product added to cart!',
                        showConfirmButton: false,
                        timer: 1500 // Automatically close after 1.5 seconds
                    });
                },
                error: function(error) {
                    console.error('Error adding to cart:', error);
                }
            });
        });
    });
</script>

@endsection