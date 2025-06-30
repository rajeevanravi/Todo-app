@extends('layouts.default')
@section('style')
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

        .error {
            color: red;
            font-weight: bold;
        }
    </style>
@endsection
@section('content')
    <main class="form-signin w-100 m-auto">
        <img src="https://i.pinimg.com/originals/52/6a/bf/526abf16cc3e74882fa7304abc0f841c.png" alt="Home Icon"
            style="width:200px; height:200px; padding: 1rem; display: block; margin: 0 auto;">
        <form id="login_form">
            @csrf
            <h1 class="h3 mb-3 fw-normal">Please sign in</h1>
            <div class="form-floating">
                <input name="email" type="email" class="form-control" id="email" placeholder="name@example.com">
                <label for="floatingInput" id ="emaillabel">Email address</label>
            </div>
            <div class="form-floating">
                <input name="password" type="password" class="form-control" id="password" placeholder="Password">
                <label id="passwordlabel" for="floatingPassword">Password</label>
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
        $(document).ready(function() {
            $('#email, #password').click(function() {
                $("#" + this.id + "label").animate({
                    left: "300px"
                }, 1000);
            });
            $('#login_form').validate({
                rules: {
                    email: {
                        required: true,
                        minlength: 3
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                },
                message: {
                    email: {
                        required: "Please enter your email",
                        minlength: "Name must be at least 3 characters",
                    },
                    password: {
                        required: "Please enter your password",
                        minlength: "Password must be at least 6 characters",
                    },
                },
                submitHandler: function(form) {
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
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top',
                                    icon: 'success',
                                    title: response.message || "Done!",
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true
                                });
                                setTimeout(function() {
                                    window.location.href = response.redirect;
                                }, 1000);
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: response.message,
                                });
                            }
                        },
                        error: function() {
                            Swal.fire({
                                title: "The Internet?",
                                text: "Something went wrong.",
                                icon: "question"
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
