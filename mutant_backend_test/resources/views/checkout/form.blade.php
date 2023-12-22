@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Checkout Form</h2>

        <!-- Add your checkout form elements here -->

        <form action="{{ route('payment.form') }}" method="get">
            @csrf
            <button type="submit" class="btn btn-success">Proceed to Payment</button>
        </form>
    </div>
@endsection
