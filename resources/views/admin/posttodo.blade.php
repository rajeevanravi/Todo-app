@extends("admin.adminlayout")
@section("adminbody")
<div class="container mt-5">
    <h2 class="mb-4">Create a New To-Do</h2>

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

        <button type="submit" id="submit" class="btn btn-primary">Add To-Do</button>
    </form>

</div>


@endsection

@section('subscripts')
    <script>
        $(document).ready(function () {
            $('#submit').click(function (e) {
                e.preventDefault();
                alert('nn')
                let addtodo_formData = {
                    title: $('#title').val(),
                    message: $('#message').val(),
                    _token: $('input[name="_token"]').val()
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
        });

    </script>

@endsection
