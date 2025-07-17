@extends('layouts.default')
@section('content')
    <nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary"
        style="width: fit-content; margin: 0 auto; padding: 10px 20px; border-radius: 8px;">
        <button id="addtodo" data-bs-toggle="modal" data-bs-target="#addtodopopup" class="btn btn-outline-success me-2"
            type="button">Add Todo</button>
        <a href="{{ route('userviewtodo') }}"><button class="btn btn-outline-success me-2" type="button">View
                Todo</button></a>
        <button id="logout" class="btn btn-sm btn-outline-danger" type="button">Logout</button>
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
                            <input type="text" name="title" id="title" class="form-control"
                                placeholder="Enter title" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea name="message" id="message" class="form-control" rows="4" placeholder="Write your task details..."
                                required></textarea>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" id="addtodosubmit" class="btn btn-primary">Save changes</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <h2 class="mb-4">View To-Do</h2>
        <div id="cardfortodo">
            @if (isset($todos) && $todos->count() > 0)
                @foreach ($todos->sortByDesc('created_at') as $todo)
                    <div class="card mb-3 todo-row" id="todo-{{ $todo->id }}">
                        <div class="card-body">
                            <h5>{{ $todo->title }}</h5>
                            <p>{{ $todo->message }}</p>
                            <div class="d-grid gap-1 d-md-flex justify-content-md-end">
                                <button class="btn btn-sm edittodo" data-bs-toggle="modal" data-bs-target="#edittodo"
                                    data-id="{{ $todo->id }}" data-title="{{ $todo->title }}"
                                    data-message="{{ $todo->message }}">
                                    <img src="https://img.icons8.com/?size=100&id=XhXVzNBHYKox&format=png&color=000000"
                                        alt="Home Icon" style="width:16px; height:16px; vertical-align:middle;">
                                </button>
                                <button class="btn btn-sm deletetodo" data-id="{{ $todo->id }}">
                                    <img src="https://img.icons8.com/?size=100&id=Pvblw74eluzR&format=png&color=000000"
                                        alt="Home Icon" style="width:16px; height:16px; vertical-align:middle;">
                                </button>
                            </div>
                            <small class="text-muted">
                                @if ($todo->created_at == $todo->updated_at)
                                    Created at: {{ $todo->created_at->format('d M Y, h:i A') }}
                                @else
                                    Last modified at: {{ $todo->updated_at->format('d M Y, h:i A') }}&nbsp;&nbsp;
                                    Created at: {{ $todo->created_at->format('d M Y, h:i A') }}
                                @endif
                            </small><br>

                            @if (auth()->user()->role === 'user')
                                <small class="text-info">User:
                                    {{ $todo->user->name }}({{ $todo->user->role }})</small>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <p>No To-Do found.</p>
            @endif
        </div>
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
                            <textarea name="message" id="editmessage" class="form-control" rows="4"
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


@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            ClassicEditor.create(document.querySelector('#message'))
                .then(editor => {
                    addEditor = editor;
                })
                .catch(error => {
                    console.error(error);
                });

            $("#todo-form").validate({
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
                    let htmlContent = addEditor ? addEditor.getData() : $('#message').val();
                    let plainText = $('<div>').html(htmlContent).text();
                    let addtodo_formData = {
                        title: $('#title').val(),
                        message: plainText,
                        /* _token: $('input[name="_token"]').val(), */
                    };
                    $.ajax({
                        type: "POST",
                        url: "{{ route('todo.store') }}",
                        data: addtodo_formData,
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

                                let newTodo = `
        <div class="card mb-3 todo-row" id="todo-${response.data.id}">
            <div class="card-body">
                <h5>${response.data.title}</h5>
                <p>${response.data.message}</p>
                <div class="d-grid gap-1 d-md-flex justify-content-md-end">
                    <button class="btn btn-sm edittodo" data-bs-toggle="modal" data-bs-target="#edittodo"
                        data-id="${response.data.id}" data-title="${response.data.title}" data-message="${response.data.message}">
                        <img src="https://img.icons8.com/?size=100&id=XhXVzNBHYKox&format=png&color=000000"
                            alt="Edit" style="width:16px; height:16px; vertical-align:middle;">
                    </button>
                    <button class="btn btn-sm deletetodo" data-id="${response.data.id}">
                        <img src="https://img.icons8.com/?size=100&id=Pvblw74eluzR&format=png&color=000000"
                            alt="Delete" style="width:16px; height:16px; vertical-align:middle;">
                    </button>
                </div>
                <small class="text-muted">
                    Created at: ${response.data.created_at}
                </small><br>
                <small class="text-info">User: ${response.data.user_name} (${response.data.user_role})</small>
            </div>
        </div>`;
                                $('#cardfortodo').prepend(newTodo);

                            } else {
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: response.message,
                                });
                            }
                        }
                    });
                }
            })
            $('#logout').click(function(e) {
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
        $(document).ready(function() {
            ClassicEditor.create(document.querySelector('#editmessage'))
                .then(editor => {
                    editEditor = editor;
                })
                .catch(error => {
                    console.error(error);
                });
            $(document).on('click', '.edittodo', function(e) {
                e.preventDefault();
                let edittodoId = $(this).data("id");
                let edittitle = $(this).data("title");
                let editmessage = $(this).data("message");
                if (typeof editEditor !== 'undefined' && editEditor) {
                    editEditor.setData(editmessage || '');
                } else {
                    $('#editmessage').val(editmessage);
                }
                let url = `/edittodos/${edittodoId}`;
                $('#edittitle').val(edittitle);
                $('#editmessage').val(editmessage);
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
                        let htmlContent = $('#editmessage').val();
                        let plainText = $('<div>').html(htmlContent).text();

                        let edittodo_formData = {
                            title: $('#edittitle').val(),
                            message: plainText,
                            /*  _token: $('input[name="_token"]').val(), */
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
                                    let htmlContent = $('#editmessage')
                                        .val();
                                    let plainText = $('<div>').html(
                                        htmlContent).text();
                                    $(`#todo-${edittodoId} p`).text(
                                        plainText);
                                    //location.reload();
                                    $('#edittodo').modal('hide');
                                    $(`#todo-${edittodoId} h5`).text($(
                                        '#edittitle').val());
                                    /* $(`#todo-${edittodoId} p`).text(
                                        plainText); */
                                    $(`#todo-${edittodoId} .edittodo`).data(
                                        'message', plainText);
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
            $(document).on('click', '.deletetodo', function(e) {
                e.preventDefault();
                let todoId = $(this).data("id");
                let url = `/todos/${todoId}`;
                let rowElement = $(this).closest('.todo-row');
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
@endsection
