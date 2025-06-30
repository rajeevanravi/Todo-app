@extends('admin.adminlayout')
@section('adminbody')
    <div class="container mt-5">
        <h2 class="mb-4">View To-Do</h2>
        <div>
            @if (isset($todos) && $todos->count() > 0)
                @foreach ($todos->sortByDesc('created_at') as $todo)
                    <div class="card mb-3">
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
                            @if (auth()->user()->role === 'admin')
                                <small class="text-info">User: {{ $todo->user->name }}({{ $todo->user->role }})</small>
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
                            <textarea name="message" id="editmessage" class="form-control" rows="4" placeholder="Write your task details..."
                                required></textarea>
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
@section('subscripts')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('.edittodo').click(function(e) {
                e.preventDefault();
                let edittodoId = $(this).data("id");
                let edittitle = $(this).data("title");
                let editmessage = $(this).data("message");
                $('#edittitle').val(edittitle);
                $('#editmessage').val(editmessage);
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
                            message: $('#editmessage').val(),
                            _token: $('input[name="_token"]').val(),
                        };
                        $.ajax({
                            url: url,
                            type: "PUT",
                            data: edittodo_formData,
                            dataType: "json",
                            success: function(response) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: response.message || "Done!",
                                    showConfirmButton: false,
                                    timer: 3000
                                }).then(() => {
                                    location.reload();
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
            $('.deletetodo').click(function(e) {
                e.preventDefault();
                let todoId = $(this).data("id");
                let url = `/todos/${todoId}`;
                console.log(todoId);
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
                            success: function(response) {
                                Swal.fire({
                                    icon: "success",
                                    title: "Deleted!",
                                    text: response.message ||
                                        "User deleted successfully.",
                                }).then(() => {
                                    location.reload();
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
