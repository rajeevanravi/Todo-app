<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TodoManager extends Controller
{

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        $role = Auth::user()->role;

        if ($role === 'admin') {

            $todos = Todo::all();
            $users = User::all();
            $todos = Todo::with('user')->get();
            return view('admin.adminlayout', compact('todos', 'users'));
        } else {
            $user = Auth::user();
            $todos = $user->todos;
            return view('user.userlayout', compact('todos'));
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
        // dd('test');
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
        $todo = Todo::create([
            'title' => $validated['title'],
            'message' => $validated['message'],
            'user_id' => Auth::id(),
        ]);
        $role = Auth::user()->role;
        if ($role === 'admin') {
            return response()->json([
                'success' => true,
                'message' => 'Add todo successfully.',
                'data' => [
                    'id' => $todo->id,
                    'title' => $todo->title,
                    'message' => $todo->message,
                    'created_at' => $todo->created_at->format('Y-m-d H:i:s'),
                    'user_name' => Auth::user()->name,
                    'user_role' => Auth::user()->role,
                ]
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Add todo successfully.',
                'data' => [
                    'id' => $todo->id,
                    'title' => $todo->title,
                    'message' => $todo->message,
                    'created_at' => $todo->created_at->format('Y-m-d H:i:s'),
                    'user_name' => Auth::user()->name,
                    'user_role' => Auth::user()->role,
                ]
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
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $todo = Todo::findOrFail($id);
        $todo->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Edit todo successfully',
            'redirect' => route('viewuser'),
            'data' => [
                'id' => $todo->id,
                'title' => $todo->title,
                'message' => $todo->message,
                'created_at' => $todo->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $todo->updated_at->format('Y-m-d H:i:s'),
                'user_name' => Auth::user()->name,
                'user_role' => Auth::user()->role,
            ]
        ]);
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
