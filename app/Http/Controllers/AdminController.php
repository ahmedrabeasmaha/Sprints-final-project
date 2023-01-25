<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    function users()
    {
        $users = User::paginate(5);
        return view('admin.users', compact('users'));
    }
}
