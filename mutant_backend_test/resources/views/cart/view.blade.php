@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Your Shopping Cart</h2>

    @forelse ($cartItems as $cartItem)
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">{{ $cartItem->product->product_name }}</h5>

            <button class="btn quantity-btn" data-id="{{ $cartItem->id }}" data-action="decrement"><i class="fas fa-minus"></i></button>
            <span id="quantity{{ $cartItem->id }}" class="mx-auto">{{ $cartItem->quantity }}</span>
            <button class="btn quantity-btn" data-id="{{ $cartItem->id }}" data-action="increment"><i class="fas fa-plus"></i></button>
            <button class="btn btn-danger float-end remove-btn" data-id="{{ $cartItem->id }}"><i class="fas fa-trash"></i></button>

            <p class="card-text">Subtotal: ${{ $cartItem->product->price }} x {{ $cartItem->quantity }} = ${{ $cartItem->quantity * $cartItem->product->price }} </p>
        </div>
    </div>
    @empty
    <h5 class="text-muted">There's nothing to see here..</h5>
    @endforelse

    @if ($cartItems->isNotEmpty())
    <div class="row">
        <div class="col">
            <p>Total Quantity in Cart: <strong>{{ $totalQuantity }}</strong></p>
        </div>
        <div class="col text-end">
            <p>Total Cost in Cart: <strong>${{ $totalCost }}</strong></p>
            <form action="{{ route('payment.form') }}" method="get">
                @csrf
                <button type="submit" class="btn btn-success">Proceed to Payment</button>
            </form>
        </div>
    </div>
    @endif

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.quantity-btn').click(function(e) {
            e.preventDefault();

            var productId = $(this).data('id');
            var action = $(this).data('action');

            $.ajax({
                url: '/cart/update',
                type: 'PATCH',
                data: {
                    id: productId,
                    action: action,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#quantity' + productId).text(response.quantity);

                    location.reload();
                },
                error: function(error) {
                    console.error('Error updating quantity:', error);
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.remove-btn').on('click', function() {
            var cartItemId = $(this).data('id');

            var currentButton = this;

            $.ajax({
                url: "{{ route('cart.remove', ['cartItemId' => '__cartItemId__']) }}".replace('__cartItemId__', cartItemId),
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    location.reload();
                },
                error: function(error) {
                    if (error.responseText) {
                        console.error(error.responseText);
                    }
                }
            });
        });
    });
</script>



@endsection