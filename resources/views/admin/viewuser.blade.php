@extends("admin.adminlayout")
@section("adminbody")
<div class="container mt-5">
    <h2 class="mb-4">User Details</h2>
    <table class="table table-borderless table-striped">

  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">ID</th>
      <th scope="col">Name</th>
      <th scope="col">e-mail</th>
      <th scope="col">Role</th>
      <th scope="col">Action</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($user as $item )
    <tr>
      <td>{{ $loop->iteration }}</td>
      <td>{{ $item->id }}</td>
      <td>{{ $item->name }}</td>
      <td>{{ $item->email }}</td>
      <td>{{ $item->role }}</td>
      <td><div style="display: flex; gap: 10px; align-items: center;">
        {{-- <a href="{{ route('users.edit', $item->id) }}"> --}}
            <button id="edit" data-bs-toggle="modal" data-bs-target="#edituser" class="btn btn-outline-info">Edit</button>
        </a>{{--  --}}
            <button class="btn btn-outline-danger delete-btn" data-id="{{ $item->id }}">Delete</button>

          </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
</div>
{{-- edit user form --}}

<div class="modal fade" id="edituser" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit user Profile</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="edituserform">

        @csrf
        @method('PUT')
        {{-- <h1 class="h3 mb-3 fw-normal">Edit user Profile</h1> --}}
        <div class="form-floating">
            <input name="name" type="text" class="form-control" id="editname" >
            <label id="namelabel" for="floatingInput">Name</label>
        </div>
        <div class="form-floating">
            <input name="email" type="email" class="form-control" id="editemail" placeholder="name@example.com">
            <label id="emaillabel" for="floatingInput">Email address</label>
        </div>
        <div class="form-floating">
            <input name="role" type="text" class="form-control" id="editrole" placeholder="name@example.com">
            <label id="rolelabel" for="floatingInput">Role</label>
        </div>
        <div class="form-floating">
            <input name="password" type="password" class="form-control" id="editpassword" placeholder="Password">
            <label id="passwordlabel" for="floatingPassword">Password</label>
        </div>
        <div class="form-check text-start my-3">
            <input class="form-check-input" type="checkbox" value="remember-me" id="checkDefault">
            <label class="form-check-label" for="checkDefault">Remember me</label>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" id="submit" class="btn btn-primary">Save changes</button>
        </div>
    </form>
      </div>

    </div>
  </div>
</div>

{{-- end edit user form --}}

@endsection

@section('subscripts')

    <script>
        $(document).ready(function () {

            let userId = $(this).data("id");

            $.validator.addMethod("validRole", function (value, element) {
               return value === "admin" || value === "user"; // Only allow "admin" or "user"
            }, "Role must be either admin or user");

            $('#name, #email, #role, #password').click(function() {
                $("#" + this.id + "label").animate({ left: "300px" }, 1000);
            });

            $('#edituserform').validate({
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
                    let edituser_formData = {
                    name: $('#editname').val(),
                    email: $('#editemail').val(),
                    password: $('#editpassword').val(),
                    role: $('#editrole').val(),
                    _token: $('input[name="_token"]').val()
                };
                /* console.log(edituser_formData); */

                 $.ajax({
                    type: "PUT",
                    dataType: "json",
                    url: "{{ route('users.update', $item->id) }}",
                    data: edituser_formData,
                    success: function (response) {

                        if (response.success) {
                        //alert(response.message);
                        Swal.fire({
                            position: "top-end",
                            icon: "success",
                            title: response.message,
                            showConfirmButton: false,
                            timer: 1500
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
                    },

                    error: function () {
                         /* alert('Something went wrong.'); */
                        Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Something went wrong.",
                            });

                    }
                });

                }
            })
        });

    </script>

    <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('.delete-btn').click(function (e) {
        e.preventDefault();
        let userId = $(this).data("id");
        let url = `/users/${userId}`; // Constructing route dynamically

        // Show confirmation dialog
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                // Proceed with AJAX deletion
                $.ajax({
                    url: url,
                    type: "DELETE",
                    dataType: "json",
                    success: function (response) {
                        Swal.fire({
                            icon: "success",
                            title: "Deleted!",
                            text: response.message || "User deleted successfully.",
                        }).then(() => {
                            location.reload();
                        });
                    },
                    error: function (xhr, status, error) {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                        });
                    }
                });
            }
        });
    });

</script>

@endsection
