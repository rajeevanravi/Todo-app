<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthManager;
use App\Http\Controllers\TodoManager;
use App\Http\Controllers\UserManager;
use App\Models\Todo;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
   return view('auth.login');
})->name(name:"home");

Route::get('login', [AuthManager::class, "login"])->name(name:"login");

Route::post('login', [AuthManager::class, "loginpost"])->name(name:"login.post");

Route::get('logout', [AuthManager::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('register', [AuthManager::class, "register"])->name(name:"register");

    Route::post('register', [AuthManager::class, "registerpost"])->name(name:"register.post");

    Route::post('/todos', [TodoManager::class, 'store'])->name('todo.store');

    Route::get('/viewtodo-admin', [TodoManager::class, 'index'])->name(name:"adminviewtodo");

    Route::get('/viewtodo-user', [TodoManager::class, 'index'])->name(name:"userviewtodo");

    Route::get('viewuser', [UserManager::class, 'index'])->name(name:"viewuser");

    Route::delete('/users/{id}', [UserManager::class, 'destroy'])->name('users.destroy');

    Route::delete('/todos/{id}', [TodoManager::class, 'destroy'])->name('todos.destroy');

    Route::get('/users/{id}/edit', [UserManager::class, 'edit'])->name('users.edit');

    Route::put('/users/{id}', [UserManager::class, 'update'])->name('users.update');

    Route::get('admindash', function () {
         return view('admin.admindash');
    })->name(name:"admin");

    Route::get('userdash', function () {
         return view('user.userdash');
    })->name(name:"user");

    Route::get('edituser', function () {
         return view('admin.edituser');
    })->name(name:"edituser");

    Route::get('adminaddtodo', function () {
        return view('admin.posttodo');
    })->name(name:"adminaddtodo");;

    Route::get('useraddtodo', function () {
        return view('user.posttodo');
    })->name(name:"useraddtodo");;


});
