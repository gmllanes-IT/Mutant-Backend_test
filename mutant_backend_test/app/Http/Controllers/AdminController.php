<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

use App\Models\User;


class AdminController extends Controller
{
    public function userList()
    {
        $users = User::all();
        return view('admin.user-list', ['users' => $users]);
    }
}
