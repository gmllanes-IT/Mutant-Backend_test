<?php
namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function edit(User $authenticatedUser, User $targetUser)
    {
        return $authenticatedUser->role === 'admin';
    }

    public function destroy(User $authenticatedUser, User $targetUser)
    {
        return $authenticatedUser->role === 'admin';
    }
}