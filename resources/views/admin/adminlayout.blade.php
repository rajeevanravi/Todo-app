@extends("layouts.default")
@section("content")

<nav  id="xx" class="navbar fixed-top navbar-expand-lg bg-body-tertiary" style="width: fit-content; margin: 0 auto; padding: 10px 20px; border-radius: 8px">
  <div class=" "  >
    <button id="addtodo" data-bs-toggle="modal" data-bs-target="#addtodopopup" class="btn btn-outline-success me-2" type="button">Add Todo</button>
    <a href="{{route("adminviewtodo")}}"><button id="viewtodo" class="btn btn-outline-success me-2" type="button">View Todo</button></a>
    <a href="{{route("viewuser")}}"><button id="viewuser" class="btn btn-outline-success me-2" type="button">View User</button></a>
    <button id="adduser" data-bs-toggle="modal" data-bs-target="#register" class="btn btn-outline-success me-2" type="button">Add User</button>
    <button id="logout" class="btn btn-sm btn-outline-danger" type="button">Logout</button>
  </div>
</nav>


<div class="modal fade" id="addtodopopup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Post To-Do</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="todo-form">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">To-Do Title</label>
            <input type="text" name="title" id="title" class="form-control" placeholder="Enter title" required>
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea name="message" id="message" class="form-control" rows="4" placeholder="Write your task details..." required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" id="addtodosubmit" class="btn btn-primary">Save changes</button>
      </div>
      </form>
    </div>
  </div>
</div>

{{-- register --}}

<div class="modal fade" id="register" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Sign up an user</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="register_form">
            @csrf
            <div class="form-floating">
                <input name="name" type="text" class="form-control" id="name" placeholder="ender the name">
                <label id="namelabel" for="floatingInput">Name</label>
            </div>
            <div class="form-floating">
                <input name="email" type="email" class="form-control" id="email" placeholder="name@example.com">
                <label id="emaillabel" for="floatingInput">Email address</label>
            </div>
            <div class="form-floating">
                <input name="role" type="text" class="form-control" id="role" placeholder="name@example.com">
                <label id="rolelabel" for="floatingInput">Role</label>
            </div>
            <div class="form-floating">
                <input name="password" type="password" class="form-control" id="password" placeholder="Password">
                <label id="passwordlabel" for="floatingPassword">Password</label>
            </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button  id="" class="btn btn-primary" type="submit">Register user</button>
            </div>
        </form>

    </div>
  </div>
</div>

@yield("adminbody")
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {
            //adduser
            $.validator.addMethod("validRole", function (value, element) {
               return value === "admin" || value === "user"; // Only allow "admin" or "user"
            }, "Role must be either admin or user");

            $('#name, #email, #role, #password').click(function() {
                $("#" + this.id + "label").animate({ left: "300px" }, 1000);
            });

             $('#register_form').validate({

                rules:{
                    name:{
                        required: true,
                        minlength: 3
                    },
                    email:{
                        required: true,
                        email: true,
                    },
                    role:{
                        required: true,
                        validRole: true,
                    },
                    password:{
                        required: true,
                        minlength: 6
                    },
                },
                message:{
                    name:{
                        required: "Please enter your name",
                        minlength: "Name must be at least 3 characters",
                    },
                    email:{
                        required: "Please enter your email address",
                        minlength: "Enter a valid email address",
                    },
                    role:{
                        required: "Please enter your role",
                        minlength: "Role must be either Admin or User",
                    },
                    password:{
                        required: "Please enter your password",
                        minlength: "Password must be at least 6 characters",
                    },
                },
                submitHandler: function (form) {
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
                        Swal.fire({
                            title: "Drag me!",
                            icon: response.message,
                            draggable: true
                        });
                        window.location.href = response.redirect;
                        } else {
                        Swal.fire({
                          icon: "error",
                          title: "Oops...",
                          text: response.message,
                        });
                        }
                    },

                    error: function () {
                        Swal.fire({
                            title: "The Internet?",
                            text: "Something went wrong.",
                            icon: "question"
                        })
                    }
                });
                }

             })
            // addtodopopup
                $("#addtodosubmit").click(function (e) {
                    e.preventDefault();


                    let addtodo_formData = {
                    title: $('#title').val(),
                    message: $('#message').val(),
                    _token: $('input[name="_token"]').val(),
                    };

                    $.ajax({
                    type: "POST",
                    url: "{{ route('todo.store') }}",
                    data: addtodo_formData,
                    dataType: "json",
                    success: function (response) {
                        if (response.success) {
                        //alert(response.message);
                        Swal.fire({
                            title: "Drag me!",
                            icon: response.message,
                            draggable: true
                        });
                        window.location.href = response.redirect;
                        } else {
                        //alert(response.message);
                        Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: response.message,
                            });
                        }
                    }



                });

                });
            //logout
            $('#logout').click(function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You will be logged out of the application.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#aaa',
                    confirmButtonText: 'Yes, logout'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                        type: "GET",
                        url: "{{ route('logout') }}",
                        dataType: "json",
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                icon: 'success',
                                title: 'Logged Out!',
                                text: response.message
                            }).then(() => {
                                window.location.href = response.redirect;
                            });
                        } else {
                            Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: response.message,
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: "Logout request failed.",
                    });
                }
            });
        }
    });
});
});
</script>
    @yield('subscripts')
@endsection
