@extends("admin.adminlayout")
@section("adminbody")
<div class="container mt-5">
    <h2 class="mb-4">View To-Do</h2>
    <div>
        @if(isset($todos) && $todos->count() > 0)
        @foreach($todos as $todo)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $todo->title }}</h5>
                    <p>{{ $todo->message }}</p>
                    <div class="d-grid gap-1 d-md-flex justify-content-md-end">
                        <button class="btn btn-sm edittodo" data-id="{{ $todo->id }}">
                            <img src="https://img.icons8.com/?size=100&id=XhXVzNBHYKox&format=png&color=000000" alt="Home Icon" style="width:16px; height:16px; vertical-align:middle;">
                        </button>
                        <button class="btn btn-sm deletetodo"  data-id="{{ $todo->id }}" >
                            <img src="https://img.icons8.com/?size=100&id=Pvblw74eluzR&format=png&color=000000" alt="Home Icon" style="width:16px; height:16px; vertical-align:middle;">
                        </button>
                    </div>
                    <small class="text-muted">
                        Created at: {{ $todo->created_at->format('d M Y, h:i A') }}
                    </small><br>

                    @if(auth()->user()->role === 'admin')
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

@endsection

@section('subscripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).ready(function () {
        $('.edittodo').click(function (e) {
            e.preventDefault();
            let todoId = $(this).data("id");
            console.log(todoId);


        });

        $('.deletetodo').click(function (e) {
            e.preventDefault();
            //let todoId= {{$todo->id}};
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
    });
</script>
@endsection
