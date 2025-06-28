<?php

namespace App\Http\Controllers;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TodoManager extends Controller
{

    public function index()
    {
        if (!Auth::check())
        {
        return redirect()->route('login');

        }
        $role = Auth::user()->role;

         if ($role === 'admin')
         {

             $todos = Todo::all();
             return view('admin.viewtodo', compact('todos'));
         }
         else
         {
             $user = Auth::user();
             $todos = $user->todos;
             return view('user.viewtodo', compact('todos'));
         }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
        'title' => 'required|string|max:255',
        'message' => 'required|string',
    ]);
        Todo::create([
        'title' => $validated['title'],
        'message' => $validated['message'],
        'user_id' => Auth::id(),
    ]);
    $role = Auth::user()->role;
    if ($role === 'admin')
    {
//return redirect(route(name:"adminaddtodo"));
        return response()->json([
            'success' => true,
            'message' => 'Add todo successfully.',
            'redirect' => route('adminviewtodo'),
        ]);
    }
    else
    {
      //  return redirect(route(name:"useraddtodo"));
      return response()->json([
            'success' => true,
            'message' => 'Add todo successfully.',
            'redirect' => route('adminviewtodo'),
        ]);
    }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todos = Todo::findOrFail($id);
        $todos->delete();
        return response()->json([
            'success' => true,
            'message' => 'Todo deleted successfully',
            'redirect' => route('viewuser'),
        ]);
    }
}
