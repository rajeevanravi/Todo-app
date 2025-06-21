@extends("admin.adminlayout")
@section("adminbody")
<main class="form-signin w-100 m-auto">
    <form method="POST" action="{{ route('users.update', $user->id) }}">

        @csrf
        @method('PUT')
        <h1 class="h3 mb-3 fw-normal">Edit user Profile</h1>
        <div class="form-floating">
            <input name="name" type="text" class="form-control" id="floatingInput" >
            <label for="floatingInput">Nmae({{$user->name}})</label>
        </div>
        <div class="form-floating">
            <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Email address({{$user->email}})</label>
        </div>
        <div class="form-floating">
            <input name="role" type="text" class="form-control" id="floatingInput" placeholder="name@example.com">
            <label for="floatingInput">Role({{$user->role}})</label>
        </div>
        <div class="form-floating">
            <input name="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
            <label for="floatingPassword">Password</label>
        </div>
        <div class="form-check text-start my-3">
            <input class="form-check-input" type="checkbox" value="remember-me" id="checkDefault">
            <label class="form-check-label" for="checkDefault">Remember me</label>
        </div>
        <button class="btn btn-primary w-100 py-2" type="submit">Edit</button>

    </form>
</main>
@endsection
