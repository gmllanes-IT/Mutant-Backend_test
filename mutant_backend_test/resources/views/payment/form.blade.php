@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Payment Form</h2>

    <form action="{{ route('payment.process') }}" method="post" id="payment-form">
        @csrf

        <div class="row">
            <div class="col">
                <div id="card-element"></div>
            </div>
            <div class="col">

                <div id="card-errors" role="alert"></div>

                <input type="hidden" name="clientSecret" value="{{ $clientSecret }}">

                <button type="submit" class="btn btn-success" id="submit-button">
                    Proceed to Payment
                </button>
            </div>
        </div>



    </form>
</div>

<!-- Include the Stripe.js library -->
<script src="https://js.stripe.com/v3/"></script>

<!-- Your JavaScript code for handling payments -->
<script>
    var stripe = Stripe('{{ config('
        services.stripe.key ') }}');
    var elements = stripe.elements();
    var card = elements.create('card');

    card.mount('#card-element');

    var form = document.getElementById('payment-form');
    var submitButton = document.getElementById('submit-button');

    form.addEventListener('submit', function(event) {
        event.preventDefault();

        submitButton.disabled = true;

        stripe.createPaymentMethod({
            type: 'card',
            card: card,
        }).then(function(result) {
            if (result.error) {
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
                submitButton.disabled = false;
            } else {
                var paymentMethodId = result.paymentMethod.id;
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'paymentMethodId');
                hiddenInput.setAttribute('value', paymentMethodId);
                form.appendChild(hiddenInput);

                stripe.confirmCardPayment('{{ $clientSecret }}', {
                    payment_method: paymentMethodId,
                }).then(function(result) {
                    if (result.error) {
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                        submitButton.disabled = false;
                    } else {
                        form.submit();
                    }
                });
            }
        });
    });
</script>
@endsection