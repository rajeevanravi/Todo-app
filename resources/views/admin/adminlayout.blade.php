@extends('layouts.default')
@section('content')
    <nav id="xxx" class="navbar fixed-top navbar-expand-lg bg-body-tertiary"
        style="width: fit-content; margin: 0 auto; padding: 10px 20px; border-radius: 8px">
        <div class=" ">
            <button id="addtodo" data-bs-toggle="modal" data-bs-target="#addtodopopup" class="btn btn-outline-success me-2"
                type="button">Add Todo</button>
            <button id="viewtodo" class="btn btn-outline-success me-2" type="button">View
                Todo</button>
            <button id="viewuser" class="btn btn-outline-success me-2" type="button">View
                User</button>
            <button id="adduser" data-bs-toggle="modal" data-bs-target="#register" class="btn btn-outline-success me-2"
                type="button">Add User</button>
            <button id="logout" class="btn btn-sm btn-outline-danger" type="button">Logout</button>
        </div>
    </nav>

    <div id="todobody" class="container">
        <div class="container mt-5" id="vtodo">
            <h2 class="mb-4">View To-Do</h2>
            @if (isset($todos) && $todos->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th>Created At</th>
                                <th>Last Modified</th>
                                @if (auth()->user()->role === 'admin')
                                    <th>User</th>
                                @endif
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="tbody">
                            @foreach ($todos->sortByDesc('created_at') as $index => $todo)
                                <tr id="todo-{{ $todo->id }}">
                                    <td>{{ $loop->iteration }}</td>
                                    <td id="title">{{ Str::limit($todo->title, 10) }}</td>
                                    <td id="message">{{ Str::limit($todo->message, 20) }}</td>
                                    <td id="cdate">{{ $todo->created_at->format('d M Y, h:i A') }}</td>
                                    <td id="mdate">
                                        @if ($todo->created_at == $todo->updated_at)
                                            Not Modified
                                        @else
                                            {{ $todo->updated_at->format('d M Y, h:i A') }}
                                        @endif
                                    </td>
                                    @if (auth()->user()->role === 'admin')
                                        <td>{{ $todo->user->name }} ({{ $todo->user->role }})</td>
                                    @endif
                                    <td>
                                        <button type="button" class="btn btn-sm btn btn-success viewtodo"
                                            data-bs-toggle="modal" data-bs-target="#exampleModal"
                                            data-id="{{ $todo->id }}" data-title="{{ $todo->title }}"
                                            data-message="{{ $todo->message }}">
                                            View
                                        </button>
                                        <button class="btn btn-sm btn btn-primary edittodo" data-bs-toggle="modal"
                                            data-bs-target="#edittodo" data-id="{{ $todo->id }}"
                                            data-title="{{ $todo->title }}" data-message="{{ $todo->message }}">
                                            Edit
                                        </button>
                                        <button class="btn btn-sm btn btn-danger deletetodo" data-id="{{ $todo->id }}">
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p>No To-Do found.</p>
            @endif
        </div>
        {{-- edit todo --}}
        <div class="modal fade" id="edittodo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Edit To-Do</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="edittodo-form">
                            @csrf
                            <div class="mb-3">
                                <label for="titlelabel" class="form-label">To-Do Title</label>
                                <input type="text" name="title" id="edittitle" class="form-control"
                                    placeholder="Enter title" required>
                            </div>
                            <div class="mb-3">
                                <label for="messagelabel" class="form-label">Message</label>
                                <textarea name="message" id="editmessage" class="form-control editm" rows="4"
                                    placeholder="Write your task details..." required></textarea>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="edittodosubmit" class="btn btn-primary">Save changes</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- view_todo --}}

        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="viewtitle">Modal title</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body">
                            <p id="viewmessage"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div id="userbody" class="container">
        <div class="container mt-5 " id="vuser">
            <h2 class="mb-4">User Details</h2>
            @if (isset($users) && $users->count() > 0)
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
                    <tbody class="foruser">

                        @foreach ($users as $item)
                            <tr class="user-row" id="user-{{ $item->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->id }}</td>
                                <td class="username">{{ $item->name }}</td>
                                <td class="useremail">{{ $item->email }}</td>
                                <td class="userrole">{{ $item->role }}</td>
                                <td>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <button data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                            data-email="{{ $item->email }}" data-role="{{ $item->role }}"
                                            data-bs-toggle="modal" data-bs-target="#edituser"
                                            class="btn btn-outline-info edit-btn">Edit</button>
                                        </a>
                                        <button class="btn btn-outline-danger delete-btn"
                                            data-id="{{ $item->id }}">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            @else
                <p>No User found.</p>
            @endif
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
                                <input name="name" type="text" class="form-control" id="editname"
                                    placeholder="">
                                <label id="editnamelabel" for="floatingInput">Name </label>
                            </div>
                            <div class="form-floating">
                                <input name="email" type="email" class="form-control" id="editemail"
                                    placeholder="name@example.com">
                                <label id="editemaillabel" for="floatingInput">Email address</label>
                            </div>
                            <div class="form-floating">
                                <input name="role" type="text" class="form-control" id="editrole"
                                    placeholder="name@example.com">
                                <label id="editrolelabel" for="floatingInput">Role</label>
                            </div>
                            <div class="form-floating">
                                <input name="password" type="password" class="form-control" id="editpassword"
                                    placeholder="Password">
                                <label id="editpasswordlabel" for="floatingPassword">Password</label>
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

    </div>
    <div class="modal fade" id="addtodopopup" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="todo-form"> <!-- ✅ Start form here -->
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Post To-Do</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">To-Do Title</label>
                            <input type="text" name="title" id="title" class="form-control ft"
                                placeholder="Enter title" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea name="message" id="messagety" class="form-control fm" rows="4"
                                placeholder="Write your task details..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" id="addtodosubmit" class="btn btn-primary">Save changes</button>
                    </div>
                </form> <!-- ✅ Close form here -->
            </div>
        </div>
    </div>
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
                            <input name="name" type="text" class="form-control" id="name"
                                placeholder="ender the name">
                            <label id="namelabel" for="floatingInput">Name</label>
                        </div>
                        <div class="form-floating">
                            <input name="email" type="email" class="form-control" id="email"
                                placeholder="name@example.com">
                            <label id="emaillabel" for="floatingInput">Email address</label>
                        </div>
                        <div class="form-floating">
                            <input name="role" type="text" class="form-control" id="role"
                                placeholder="name@example.com">
                            <label id="rolelabel" for="floatingInput">Role</label>
                        </div>
                        <div class="form-floating">
                            <input name="password" type="password" class="form-control" id="password"
                                placeholder="Password">
                            <label id="passwordlabel" for="floatingPassword">Password</label>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="" class="btn btn-primary" type="submit">Register user</button>
                </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#userbody').hide();
            $('#viewtodo').on('click', function() {
                $('#userbody').hide();
                $('#todobody').show();
            });

            $('#viewuser').on('click', function() {
                $('#todobody ').hide();
                $('#userbody').show();
            });
            //adduser
            $.validator.addMethod("validRole", function(value, element) {
                return value === "admin" || value === "user";
            }, "Role must be either admin or user");

            $('#name, #email, #role, #password').click(function() {
                $("#" + this.id + "label").animate({
                    left: "300px"
                }, 1000);
            });
            $('#register_form').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    role: {
                        required: true,
                        validRole: true,
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                },
                message: {
                    name: {
                        required: "Please enter your name",
                        minlength: "Name must be at least 3 characters",
                    },
                    email: {
                        required: "Please enter your email address",
                        minlength: "Enter a valid email address",
                    },
                    role: {
                        required: "Please enter your role",
                        minlength: "Role must be either Admin or User",
                    },
                    password: {
                        required: "Please enter your password",
                        minlength: "Password must be at least 6 characters",
                    },
                },
                submitHandler: function(form) {
                    let register_formData = {
                        name: $('#name').val(),
                        email: $('#email').val(),
                        password: $('#password').val(),
                        role: $('#role').val(),
                    };
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "{{ route('register.post') }}",
                        data: register_formData,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: response.message || "Done!",
                                    showConfirmButton: false,
                                    timer: 1000
                                });

                                $('#register').modal('hide');

                                let newRow = `
                                    <tr class="user-row" id="user-${response.data.id}">
                                        <td>${response.data.total_users}</td>
                                        <td>${response.data.id}</td>
                                        <td class="username">${response.data.name}</td>
                                        <td class="useremail">${response.data.email}</td>
                                        <td class="userrole">${response.data.role}</td>
                                        <td>
                                            <div style="display: flex; gap: 10px; align-items: center;">
                                                <button data-id="${response.data.id}" data-name="${response.data.name}"
                                                    data-email="${response.data.email}" data-role="${response.data.role}" data-bs-toggle="modal"
                                                    data-bs-target="#edituser" class="btn btn-outline-info edit-btn">Edit</button>
                                                <button class="btn btn-outline-danger delete-btn"
                                                    data-id="${response.data.id}">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                            `;

                                $('.foruser').append(newRow);
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
                            })
                        }
                    });
                }
            })
            // addtodopopup
            $("#todo-form").validate({
                rules: {
                    title: {
                        required: true,
                        minlength: 3
                    },
                    message: {
                        required: true,
                        minlength: 3
                    }
                },
                messages: {
                    title: {
                        required: "Please enter the title",
                        minlength: "Title must be at least 3 characters"
                    },
                    message: {
                        required: "Please type your todo",
                        minlength: "Message must be at least 3 characters"
                    }
                },
                submitHandler: function(form) {
                    let todoFormData = new FormData();
                    todoFormData.append('title', $('.ft').val());
                    /* todoFormData.append('message', $('.fm').val()); */
                    todoFormData.append('message', tinymce.get('messagety').getContent({
                        format: 'text'
                    }));
                    todoFormData.append('_token', $('meta[name="csrf-token"]').attr(
                        'content')); // good to include CSRF
                    $.ajax({
                        type: "POST",
                        url: "{{ route('todo.store') }}",
                        data: todoFormData,
                        processData: false, // ⚠️ required for FormData
                        contentType: false, // ⚠️ required for FormData
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: response.message || "Done!",
                                    showConfirmButton: false,
                                    timer: 500
                                });
                                $('#addtodopopup').modal('hide');
                                $('#todo-form')[0].reset(); // clear form

                                let newTodoRow = `
                                            <tr id="todo-${response.data.id}">
                                                <td>New</td>
                                                <td id="title">${response.data.title.substring(0, 10)}</td>
                                                <td id="message">${response.data.message.substring(0, 20)}</td>
                                                <td id="cdate">${response.data.created_at}</td>
                                                <td id="mdate">Not Modified</td>
                                                <td>${response.data.user_name} (${response.data.user_role})</td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success viewtodo"
                                                        data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                        data-id="${response.data.id}" data-title="${response.data.title}"
                                                        data-message="${response.data.message}">
                                                        View
                                                    </button>
                                                    <button class="btn btn-sm btn-primary edittodo" data-bs-toggle="modal"
                                                        data-bs-target="#edittodo" data-id="${response.data.id}"
                                                        data-title="${response.data.title}" data-message="${response.data.message}">
                                                        Edit
                                                    </button>
                                                    <button class="btn btn-sm btn-danger deletetodo" data-id="${response.data.id}">
                                                        Delete
                                                    </button>
                                                </td>
                                            </tr>`;
                                $('.tbody').prepend(newTodoRow);
                                updateSerialNumbers();
                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: response.message,
                                });
                            }
                        },
                        error: function(xhr) {
                            console.log(xhr.responseJSON);
                            Swal.fire("Validation Error",
                                "Please fill all required fields correctly", "error");
                        }
                    });
                }
            });

            $(document).off('click', '#logout').on('click', '#logout', function(e) {
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
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Logged Out!',
                                        text: response.message
                                    }).then(() => {
                                        window.location.href = response
                                            .redirect;
                                    });
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
    <script>
        function updateSerialNumbers() {
            $('tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }
        $(document).ready(function() {
            // Use event delegation for all todo action buttons
            $(document).on('click', '.edittodo', function(e) {
                e.preventDefault();
                let edittodoId = $(this).data("id");
                let edittitle = $(this).data("title");
                let editmessage = $(this).data("message");
                $('#edittitle').val(edittitle);
                /* $('#editmessage').val(editmessage); */
                if (tinymce.get('editmessage')) {
                    tinymce.get('editmessage').setContent(editmessage || '');
                }
                let url = `/edittodos/${edittodoId}`;
                $("#edittodo-form").validate({
                    rules: {
                        title: {
                            required: true,
                            minlength: 3
                        },
                        message: {
                            required: true,
                            minlength: 3,
                        },
                    },
                    message: {
                        title: {
                            required: "Please enter the title",
                            minlength: "title must be at least 3 characters",
                        },
                        message: {
                            required: "Please type your todo",
                            minlength: "title must be at least 3 characters",
                        },
                    },
                    submitHandler: function(form) {
                        let edittodo_formData = {
                            title: $('#edittitle').val(),
                            message: tinymce.get('editmessage').getContent({
                                format: 'text'
                            })
                            /* _token: $('input[name="_token"]').val(), */
                        };

                        $.ajax({
                            url: url,
                            type: "PUT",
                            data: edittodo_formData,
                            dataType: "json",
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            },
                            success: function(response) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: response.message || "Done!",
                                    showConfirmButton: false,
                                    timer: 500
                                }).then(() => {
                                    /* location.reload(); */
                                    $('#edittodo').modal('hide');
                                    $(`#todo-${edittodoId} #title`).text(
                                        response.data.title);
                                    $(`#todo-${edittodoId} #message`).text(
                                        response.data.message);
                                    $(`#todo-${edittodoId} #cdate`).text(
                                        response.data.created_at);
                                    $(`#todo-${edittodoId} #mdate`).text(
                                        response.data.updated_at);
                                });
                            },
                            error: function(xhr, status, error) {
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: "Something went wrong!",
                                });
                            }
                        });
                    }
                })
            });
            $(document).on('click', '.viewtodo', function(e) {
                e.preventDefault();
                let title = $(this).data('title');
                let message = $(this).data('message');
                $('#viewtitle').html(title);
                $('#viewmessage').html(message);
            });

            $(document).on('click', '.deletetodo', function(e) {
                e.preventDefault();
                let todoId = $(this).data("id");
                let url = `/todos/${todoId}`;
                let rowElement = $(`#todo-${todoId}`);
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
                        $.ajax({
                            url: url,
                            type: "DELETE",
                            data: {
                                _token: $('meta[name="csrf-token"]').attr(
                                    'content') // ✅ required
                            },
                            dataType: "json",
                            success: function(response) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Deleted!",
                                    text: response.message ||
                                        "User deleted successfully.",
                                }).then(() => {
                                    rowElement.remove();
                                    updateSerialNumbers();
                                });
                            },
                            error: function(xhr, status, error) {
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
        });
    </script>
    <script>
        $(document).ready(function() {
            $(document).on('click', '.edit-btn', function(e) {
                e.preventDefault();
                let userId = $(this).data("id");
                let url = `/users/${userId}`;
                let username = $(this).data("name");
                let useremail = $(this).data("email");
                let userrole = $(this).data("role");
                $('#editname').val(username).one('focus', function() {
                    $(this).val('');
                });
                $('#editemail').val(useremail).one('focus', function() {
                    $(this).val('');
                });
                $('#editrole').val(userrole).one('focus', function() {
                    $(this).val('');
                });
                $('#editname').val(username).one('focus', function() {
                    $(this).val('');
                });
                $('#editpassword').val('').one('focus', function() {
                    $(this).val('');
                });
                $.validator.addMethod("validRole", function(value, element) {
                    return value === "admin" || value === "user";
                }, "Role must be either admin or user");
                $('#editname, #editemail, #editrole, #editpassword').click(function() {
                    $("#" + this.id + "label").animate({
                        left: "300px"
                    }, 1000);
                });
                $('#edituserform').validate({
                    rules: {
                        name: {
                            required: true,
                            minlength: 3
                        },
                        email: {
                            required: true,
                            email: true,
                        },
                        role: {
                            required: true,
                            validRole: true,
                        },
                        password: {
                            required: true,
                            minlength: 6
                        },
                    },
                    message: {
                        name: {
                            required: "Please enter your name",
                            minlength: "Name must be at least 3 characters",
                        },
                        email: {
                            required: "Please enter your email address",
                            minlength: "Enter a valid email address",
                        },
                        role: {
                            required: "Please enter your role",
                            minlength: "Role must be either Admin or User",
                        },
                        password: {
                            required: "Please enter your password",
                            minlength: "Password must be at least 6 characters",
                        },
                    },
                    submitHandler: function(form) {
                        let edituser_formData = {
                            name: $('#editname').val(),
                            email: $('#editemail').val(),
                            password: $('#editpassword').val(),
                            role: $('#editrole').val(),
                        };
                        $.ajax({
                            type: "PUT",
                            dataType: "json",
                            url: url,
                            data: edituser_formData,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content')
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        toast: true,
                                        position: 'top',
                                        icon: 'success',
                                        title: response.message || "Done!",
                                        showConfirmButton: false,
                                        timer: 500,
                                        timerProgressBar: true
                                    });
                                    setTimeout(function() {
                                        $('#edituser').modal('hide');
                                        $(`#user-${userId} .username`).text(
                                            $('#editname').val());
                                        $(`#user-${userId} .useremail`)
                                            .text($('#editemail').val());
                                        $(`#user-${userId} .userrole`).text(
                                            $('#editrole').val());
                                    }, 10);
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
                                    icon: "error",
                                    title: "Oops...",
                                    text: "Something went wrong.",
                                });
                            }
                        });
                    }
                })
            });
        });
    </script>
    <script>
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            let userId = $(this).data("id");
            let url = `/users/${userId}`;
            let rowElement = $(this).closest('.user-row');
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
                    $.ajax({
                        url: url,
                        type: "DELETE",
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: "success",
                                title: "Deleted!",
                                text: response.message || "User deleted successfully.",
                            }).then(() => {
                                rowElement.remove();
                            });
                        },
                        error: function(xhr, status, error) {
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tinymce.init({
                selector: '#messagety', // Make sure your textarea has id="messagety"
                height: 200,
                plugins: [
                    'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link',
                    'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                    'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed',
                    'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable',
                    'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments',
                    'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography',
                    'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'
                ],
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                tinycomments_mode: 'embedded',
                tinycomments_author: 'Author name',
                mergetags_list: [{
                        value: 'First.Name',
                        title: 'First Name'
                    },
                    {
                        value: 'Email',
                        title: 'Email'
                    },
                ],
                ai_request: (request, respondWith) => respondWith.string(() => Promise.reject(
                    'See docs to implement AI Assistant')),
                setup: function(editor) {
                    editor.on('change', function() {
                        tinymce.triggerSave(); // Sync content to textarea
                    });
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tinymce.init({
                selector: '#messagety', // Make sure your textarea has id="messagety"
                height: 200,
                plugins: [
                    'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link',
                    'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                    'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed',
                    'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable',
                    'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments',
                    'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography',
                    'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'
                ],
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                tinycomments_mode: 'embedded',
                tinycomments_author: 'Author name',
                mergetags_list: [{
                        value: 'First.Name',
                        title: 'First Name'
                    },
                    {
                        value: 'Email',
                        title: 'Email'
                    },
                ],
                ai_request: (request, respondWith) => respondWith.string(() => Promise.reject(
                    'See docs to implement AI Assistant')),
                setup: function(editor) {
                    editor.on('change', function() {
                        tinymce.triggerSave(); // Sync content to textarea
                    });
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            tinymce.init({
                selector: '#editmessage', // Make sure your textarea has id="messagety"
                height: 200,
                plugins: [
                    'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link',
                    'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                    'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed',
                    'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable',
                    'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments',
                    'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography',
                    'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'
                ],
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                tinycomments_mode: 'embedded',
                tinycomments_author: 'Author name',
                mergetags_list: [{
                        value: 'First.Name',
                        title: 'First Name'
                    },
                    {
                        value: 'Email',
                        title: 'Email'
                    },
                ],
                ai_request: (request, respondWith) => respondWith.string(() => Promise.reject(
                    'See docs to implement AI Assistant')),
                setup: function(editor) {
                    editor.on('change', function() {
                        tinymce.triggerSave(); // Sync content to textarea
                    });
                    editor.on('init', function(e) {


                        editor.setContent("");
                    });
                }
            });
        });
    </script>
@endsection
