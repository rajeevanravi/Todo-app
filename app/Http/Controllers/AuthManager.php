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

    function login()
    {
        return view(view: 'auth.login');
    }

    function loginpost(Request $request)
    {
        // using this for pure laravel login and defind the post and method in form in blade file......


        /* $request->validate([
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
        return redirect(route(name:"login"))->with("error","try again"); */

        // for Ajex login logic
        $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();

        $redirectUrl = $user->role === 'admin'
            ? route('adminviewtodo')
            : route('userviewtodo');

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
        return view(view:'auth.register');
    }

    function registerpost(Request $request)
    {
        // this for pure laravel login if useing this then enable method and action in register form
        /* $request->validate([
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
        return redirect(route(name:"register"));*/

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
           return response()->json([
            'success' => true,
            'message' => 'Add user successfully.',
            'redirect' => route('viewuser'),
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
