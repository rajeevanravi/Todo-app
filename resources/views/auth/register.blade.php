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
    <form id="register_form">
        @csrf

        <h1 class="h3 mb-3 fw-normal">Sign up an user</h1>
        <div class="form-floating">
            <input name="name" type="text" class="form-control" id="name" placeholder="ender the name">
            <label for="floatingInput">Name</label>
            @error('name')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-floating">
            <input name="email" type="email" class="form-control" id="email" placeholder="name@example.com">
            <label for="floatingInput">Email address</label>
             @error('email')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-floating">
            <input name="role" type="text" class="form-control" id="role" placeholder="name@example.com">
            <label for="floatingInput">Role</label>
            @error('role')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>
        <div class="form-floating">
            <input name="password" type="password" class="form-control" id="password" placeholder="Password">
            <label for="floatingPassword">Password</label>
            @error('password')
                <span class="text-danger">{{$message}}</span>
            @enderror
        </div>

        <div style="display: flex; gap: 10px; align-items: center;">
            <button id="signup" class="btn btn-outline-primary w-100 " type="submit">Sign up</button>

        </div>
    </form><br>
    <a href="{{route("viewuser")}}"><button class="btn btn-outline-danger w-100 " >Cancel</button></a>
</main>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#signup').click(function (e) {
                e.preventDefault();

                let register_formData = {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                    role: $('#role').val(),
                    _token: $('input[name="_token"]').val()
                };


                $.ajax({
                    type: "POST",
                    dataType: "json",
                    url: "{{route('register.post')}}",
                    data: register_formData,
                    success: function (response) {
                        if (response.success) {
                        alert(response.message);
                        window.location.href = response.redirect;
                        } else {
                        alert(response.message);
                        }
                    },

                    error: function () {
                        alert('Something went wrong.');
                    }
                });


            });
        });
    </script>
@endsection
