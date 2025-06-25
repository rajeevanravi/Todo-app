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
    <form id="login_form">
        @csrf
        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
        <div class="form-floating">
            <input name="email" type="email" class="form-control" id="email" placeholder="name@example.com">
            <label for="floatingInput">Email address</label>
            @error('email')
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
        <div class="form-check text-start my-3">
            <input class="form-check-input" type="checkbox" value="remember-me" id="checkDefault">
            <label class="form-check-label" for="checkDefault">Remember me</label>
        </div>
        <button class="btn btn-outline-primary w-100 py-2" type="submit" id="signin">Sign in</button>

    </form>
</main>

@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
        $('#signin').click(function (e) {
            e.preventDefault();

            let login_formData = {
                email: $('#email').val(),
                password: $('#password').val(),
                _token: $('input[name="_token"]').val()
            };


             $.ajax({
                type: "POST",
                url: "{{ route('login.post') }}",
                data: login_formData,
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        alert(response.message);
                        window.location.href = response.redirect;
                    } else {
                        alert('Try again');
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
