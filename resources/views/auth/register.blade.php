@extends("layouts.default")
@section("style")
<style>
    html,
body {
  height: 100%;
}

.form-signin {
  max-width: 330px;
  padding: 1rem;
}

.form-signin .form-floating:focus-within {
  z-index: 2;
}

.form-signin input[type="email"] {
  margin-bottom: -1px;
  border-bottom-right-radius: 0;
  border-bottom-left-radius: 0;
}

.form-signin input[type="password"] {
  margin-bottom: 10px;
  border-top-left-radius: 0;
  border-top-right-radius: 0;
}
</style>
@endsection
@section("content")
<main class="form-signin w-100 m-auto">
    <form method="POST" action="{{route("register.post")}}">
        @csrf

        <h1 class="h3 mb-3 fw-normal">Sign up an user</h1>
        <div class="form-floating">
            <input name="name" type="text" class="form-control" id="floatingInput" placeholder="ender the name">
            <label for="floatingInput">Name</label>
            @error('name')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-floating">
            <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Email address</label>
             @error('email')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-floating">
            <input name="role" type="text" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Role</label>
            @error('role')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-floating">
            <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
            <label for="floatingPassword">Password</label>
            @error('password')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

        <div style="display: flex; gap: 10px; align-items: center;">
            <button class="btn btn-outline-primary w-100 " type="submit">Sign up</button>

        </div>
    </form><br>
    <a href="{{route("viewuser")}}"><button class="btn btn-outline-danger w-100 " >Cancel</button></a>
</main>
@endsection
