<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthManager extends Controller
{
    function login()
    {
        return view(view: 'auth.login');
    }

    function loginpost(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only(['email', 'password']);
        if(Auth::attempt($credentials))
        {
            if (Auth::user()->role == 'admin')
            {
               // return redirect(route(name:"admin"));
                return redirect(route(name:"adminaddtodo"));
            }
            elseif (Auth::user()->role == 'user')
            {
                return redirect(route(name:"useraddtodo"));
            }


           // return redirect()->intended(route(name:"home"));
        }
        return redirect(route(name:"login"))->with("error","try again");
    }

    function register()
    {
        return view(view:'auth.register');
    }

    function registerpost(Request $request)
    {
        $request->validate([
            'name' =>'required',
            'email' =>'required|email',
            'password' =>'required|min:6',
            'role' =>'required',
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->role = $request->role;
        if($user->save())
        {
            return redirect(route(name:"viewuser"));
        }
        return redirect(route(name:"register"));
    }
    function logout(Request $request)
    {
            $user = Auth::user();

         Auth::logout(); // Log the user out


        $request->session()->invalidate(); // Invalidate session
        $request->session()->regenerateToken(); // Prevent CSRF reuse

        return redirect(route(name:"login")); // Redirect to login page
    }
}
