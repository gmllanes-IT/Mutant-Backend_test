<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

use App\Models\User;
use App\Models\Transaction;

class UserController extends Controller
{
    public function show(Request $request)
    {
        $transactions = Transaction::where('user_id', $request->user()->id)->get();
        return view('profile.index', compact('transactions'));
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            ],
        ]);

        $data = [
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->input('password'));
        }

        $user->update($data);

        if (User::count() === 1 && $user->role === 'user') {
            return redirect()->route('profile')->with('promote_prompt', true)->with('success', 'Profile updated successfully.');
        }

        return redirect()->route('profile')->with('success', 'Profile updated successfully.');
    }

    public function promoteToAdmin()
    {
        $user = auth()->user();

        if (User::count() === 1) {
            $user->update(['role' => 'admin']);
            return redirect()->route('profile')->with('promote_prompt', true)->with('success', 'Profile updated successfully.');
        }

        return redirect()->route('profile.edit')->with('error', 'Unable to promote to admin.');
    }
    public function editUser(User $user)
    {
        $this->authorize('edit', $user);

        return view('modals.edit-user', compact('user'));
    }
    public function updateUser(Request $request, User $user)
    {
        // Validate and update the user (you may want to add additional validation)
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin',
        ]);

        $user->update([
            'first_name' => $request->input('first_name'),
            'last_name' => $request->input('last_name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
        ]);

        // Redirect to the user list with a success message
        return redirect()->route('user.list')->with('success', 'User updated successfully.');

        // Redirect to the user list with a success message
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('user.list')->with('success', 'User deleted successfully.');
    }
}
