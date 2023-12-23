@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('User Profile') }}</div>

                <div class="card-body">
                    <p>Welcome, {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}!</p>
                    <p>Email: {{ Auth::user()->email }}</p>
                    <p>Role: {{ Auth::user()->role }}</p>

                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>

                    @if(session('promote_prompt'))
                    <form method="POST" action="{{ route('promote.to.admin') }}" class="mt-3">
                        @csrf
                        <button type="submit" class="btn btn-warning" onclick="return confirm('Are you sure you want to promote to admin?')">Promote to Admin</button>
                    </form>
                    @endif

                </div>
            </div>

        </div>
    </div>
</div>
<br>

@if (Auth::user()->role === 'user')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Transaction History</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Payment Intent ID</th>
                                <th>Amount</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->id }}</td>
                                <td>{{ $transaction->payment_intent_id }}</td>
                                <td>${{ $transaction->amount }}</td>
                                <td>{{ $transaction->created_at }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">No transactions found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
</div>
@endif
@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success!',
        text: '{{ session('
        success ') }}',
    });
</script>
@endif
@endsection