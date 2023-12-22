@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Error Processing Payment</h2>

        <p>Oops! Something went wrong with your payment. Please try again later or contact support.</p>

        <a href="{{ route('home') }}" class="btn btn-primary">Return to Home</a>
    </div>
@endsection
