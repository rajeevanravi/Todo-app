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
        <a href="{{ route('users.edit', $item->id) }}">
            <button id="edit" class="btn btn-outline-info">Edit</button>
        </a>
            <button class="btn btn-outline-danger delete-btn" data-id="{{ $item->id }}">Delete</button>

          {{-- <form id="deleteform">
            @csrf
            @method('DELETE')
        <button id="delete" type="submit" class="btn btn-outline-danger" >Delete</button>
        </form> --}}  </div>
      </td>
    </tr>
    @endforeach
  </tbody>
</table>
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
            $('.delete-btn').click(function (e) {
                e.preventDefault();

                let userId = $(this).data("id"); // Get user ID
                let url = "{{ route('users.destroy', $item->id) }}";
                console.log(userId); console.log(url);
                if (confirm("Are you sure you want to delete this user?")) {

                    $.ajax({
                        url: url,
                        type: "DELETE",
                        dataType: "json",
                        success: function () {
                            alert("User deleted successfully!");
                            location.reload(); // Reloads the current page

                        },
                        error: function(xhr, status, error) {
                            alert("Error deleting user: " + error);
                            console.log(xhr.responseText);
                        }
            });
                }






            });
        });
    </script>

@endsection
