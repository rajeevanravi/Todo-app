@extends("layouts.default")
@section("content")
<nav class="navbar fixed-top navbar-expand-lg bg-body-tertiary" style="width: fit-content; margin: 0 auto; padding: 10px 20px; border-radius: 8px;">
  <div class="">
    {{-- <a href="{{route("useraddtodo")}}"><button class="btn btn-outline-success me-2" type="button">Add Todo</button></a> --}}
        <button id="addtodo" data-bs-toggle="modal" data-bs-target="#addtodopopup" class="btn btn-outline-success me-2" type="button">Add Todo</button>
    <a href="{{route("userviewtodo")}}"><button class="btn btn-outline-success me-2" type="button">View Todo</button></a>
    <button id="logout" class="btn btn-sm btn-outline-danger" type="button">Logout</button>
  </div>
</nav>
{{--  add todo by popup --}}

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

@yield("userbody")
@endsection
@section('scripts')
    <script>
        $(document).ready(function () {

            $("#addtodosubmit").click(function (e) {
                e.preventDefault();

                let addtodo_formData = {
                    title: $('#title').val(),
                    message: $('#message').val(),
                    _token: $('input[name="_token"]').val(),
                    };
               // console.log(addtodo_formData);

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
