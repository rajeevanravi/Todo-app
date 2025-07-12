<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Response;

class AuthManager extends Controller
{
    /* public function loadView(Request $request)
    {
        $type = $request->type;
        if ($type === 'user') {
            return view('admin.viewuser')->render();
        } elseif ($type === 'todo') {
            return view('admin.viewtodo')->render();
        }
        return response('Invalid view', 404);
    } */
    function login()
    {
        return view(view: 'auth.login');
    }

    function loginpost(Request $request)
    {


        // for Ajex login logic
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            $redirectUrl = $user->role === 'admin'
                ? route('admin')
                : route('user');

            return response()->json([
                'success' => true,
                'message' => 'Login successfully.',
                'redirect' => $redirectUrl,
            ]);
        }


        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password.',
        ]);
    }

    function register()
    {
        return view(view: 'auth.register');
    }

    function registerpost(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'role' => 'required',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->role = $request->role;

        $totalUsers = User::count() + 1;

        if ($user->save()) {
            return response()->json([
                'success' => true,
                'message' => 'Add user successfully.',
                'redirect' => route('viewuser'),
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'total_users' => $totalUsers,
                ]
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'try again.',

        ]);
    }
    function logout(Request $request)
    {
        // $user = Auth::user();

        Auth::logout(); // Log the user out


        $request->session()->invalidate(); // Invalidate session
        $request->session()->regenerateToken(); // Prevent CSRF reuse

        // return redirect(route(name:"login")); // Redirect to login page
        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
            'redirect' => route('login'),
        ]);
    }
}
