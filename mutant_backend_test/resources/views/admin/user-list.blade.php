@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('User List') }}</div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->first_name }} {{ $user->last_name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                                <td>
                                    <a href="#" data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>


                                    <form method="post" action="{{ route('users.destroy', ['user' => $user->id]) }}" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn" title="Delete" onclick="return confirm('Are you sure you want to delete this user?')" style="color: red;">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @include('modals.edit-user', ['user' => $user])

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
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